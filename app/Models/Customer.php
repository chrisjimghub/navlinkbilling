<?php

namespace App\Models;

use App\Models\User;
use App\Models\Model;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;

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
        return $this->hasOne(User::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    // Define a scope to query by full name
    public function scopeWhereFullName($query, $fullName)
    {
        $nameParts = explode(', ', $fullName, 2);
        if (count($nameParts) === 2) {
            return $query->where('last_name', $nameParts[0])
                         ->where('first_name', $nameParts[1]);
        }

        // Handle cases where the input is not in the expected format
        return $query->whereRaw('1 = 0'); // Always false
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getAddressAttribute()
    {
        return "{$this->city_or_municipality}, {$this->barangay}, {$this->block_street}";
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
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
