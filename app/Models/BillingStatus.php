<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Billing;


class BillingStatus extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'billing_statuses';
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
    public function billings()
    {
        return $this->hasMany(Billing::class);
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
    public function getBadgeAttribute()
    {
        $class = '';

        if ($this->id == 1 || $this->id == 4) { // paid & harvested
            $class = 'text-success';
        }elseif ($this->id == 2) { // unpaid
            $class = 'text-danger';
        }elseif ($this->id == 3) { // pending...
            $class = 'text-warning';
        }else {
            $class = 'text-default';
        }

        return "<strong class='".$class."'>{$this->name}</strong>";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
