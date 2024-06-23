<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Account;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class Otc extends Model
{
    use CurrencyFormat;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'otcs';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_otc', 'otc_id', 'account_id')->withTimestamps();
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
    public function getAmountNameAttribute()
    {
        $amount = $this->amount;

        
        if ($amount == 0) {
            $amount = '';
        }else {
            $amount = $this->currencyFormatAccessor($amount). ' ';
        }

        return $amount . $this->name;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
