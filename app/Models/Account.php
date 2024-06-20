<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Traits\LogsActivity;
use App\Models\PlannedApplicationType;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'accounts';
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
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function plannedApplicationType()
    {
        return $this->belongsTo(PlannedApplicationType::class);
    }

    public function plannedApplication()
    {
        return $this->belongsTo(PlannedApplication::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // public function otcs()
    // {
    //     return $this->belongsToMany(Otc::class, 'customer_otc', 'customer_id', 'otc_id');
    // }

    // public function contractPeriods()
    // {
    //     return $this->belongsToMany(ContractPeriod::class, 'contract_period_customer', 'customer_id', 'contract_period_id')->withTimestamps();
    // }

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
