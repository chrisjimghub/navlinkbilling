<?php

namespace App\Models;

use App\Models\Otc;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use App\Models\Traits\LogsActivity;
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

    public function plannedApplication()
    {
        return $this->belongsTo(PlannedApplication::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function otcs()
    {
        return $this->belongsToMany(Otc::class, 'account_otc', 'account_id', 'otc_id')->withTimestamps();
    }

    public function contractPeriods()
    {
        return $this->belongsToMany(ContractPeriod::class, 'account_contract_period', 'account_id', 'contract_period_id')->withTimestamps();
    }

    public function accountStatus()
    {
        return $this->belongsTo(AccountStatus::class);
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}