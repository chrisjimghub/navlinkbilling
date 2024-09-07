<?php

namespace App\Models;

use App\Models\User;
use App\Models\Model;
use App\Models\Status;
use App\Models\Account;
use App\Models\Category;
use App\Models\PaymentMethod;

class HotspotVoucher extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'hotspot_vouchers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    protected $casts = [
        'bank_details' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function isPaid() : bool
    {
        return $this->status_id == 1 ? true : false;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopePaid($query)
    {
        return $query->where('status_id', 1); 
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status_id', 2); 
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
