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
    public function parseAmountName($amountName)
    {
        // Use regex to extract the amount and name
        $matches = [];
        if (preg_match('/^(?:₱(\d+[\d,]*\.?\d*) )?(.*)$/', $amountName, $matches)) {
            // Extract and convert the amount to float, default to 0.0 if not present
            $amount = isset($matches[1]) ? (float)str_replace(',', '', $matches[1]) : 0.0;
            $name = $matches[2];

            return [
                'amount' => $amount,
                'name' => $name
            ];
        }

        // If parsing fails, return default values (0 for amount, and the whole string as name)
        return [
            'amount' => 0.0,
            'name' => $amountName
        ];
    }



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
    public function scopeWhereAmountName($query, $amountName)
    {
        $parsed = $this->parseAmountName($amountName);

        if ($parsed) {
            return $query->where('amount', $parsed['amount'])
                        ->where('name', $parsed['name']);
        }

        return $query->whereRaw('1 = 0'); // Always false if parsing fails
    }



    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // NOTE:: if by some chance you change this, please check or change also the above method parseAmountName
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
