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

        switch ($this->id) {
            case 1:
            case 4:
                $class = 'text-success';
                break;

            case 2:
            case 5:
                $class = 'text-danger';
                break;
            
            case 3:
                $class = 'text-warning';
                break;
                
            default:
                $class = 'text-default';
                break;
        }

        return "<strong class='".$class."'>{$this->name}</strong>";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
