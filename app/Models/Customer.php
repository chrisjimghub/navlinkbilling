<?php

namespace App\Models;

use App\Models\User;
use App\Models\Model;
use App\Models\Account;
use Illuminate\Support\Str;
use App\Models\CustomerCredit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot() 
    {
        parent::boot();

        static::addGlobalScope('orderByFullName', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $orderBy = 'asc';
            $builder->orderBy('last_name', $orderBy);
            $builder->orderBy('first_name', $orderBy);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerCredits()
    {
        return $this->hasMany(CustomerCredit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    // Scope method to filter customers with remaining credits > 0
    public function scopeHasRemainingCredits($query)
    {
        return $query->whereHas('customerCredits', function ($query) {
            $query->where('amount', '>', 0);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
    }
    
    /**
     * Get the customer's remaining credits.
     *
     * @return float
     */
    public function getRemainingCreditsAttribute()
    {
        $result = $this->customerCredits()
            ->select(DB::raw('SUM(amount) as total_credits'))
            ->first();

        return $result ? $result->total_credits : 0;
    }

    /**
     * Get the latest update date of the customer's credits.
     *
     * @return string
     */
    public function getCreditsLatestUpdatedAttribute()
    {
        $result = $this->customerCredits()
            ->select(DB::raw('MAX(created_at) as latest_created_at'))
            ->first();

        return $result ? $result->latest_created_at : null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setSignatureAttribute($value)
    {
        if (Str::startsWith($value, 'data:image/png;base64,')) {
            $base64Image = substr($value, strpos($value, ',') + 1);
            $image = base64_decode($base64Image);

            $imageName = 'signature_' . time() . '.png'; // Generate a unique image name
            $path = 'signature/' . $imageName;

            Storage::disk('public')->put($path, $image);

            $this->attributes['signature'] = $path;
        } else {
            $this->attributes['signature'] = $value;
        }
    }

    public function setPhotoAttribute($value)
    {
        $attribute_name = "photo";
        $disk = "public";
        $destination_path = "photos";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

}
