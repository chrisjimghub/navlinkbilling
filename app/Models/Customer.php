<?php

namespace App\Models;

use App\Models\User;
use App\Models\Model;
use App\Models\Account;
use Illuminate\Support\Str;
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
    // Revisson
    public function identifiableName()
    {
        return $this->fullName;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
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

    


}
