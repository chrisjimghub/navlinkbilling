<?php

namespace App\Models;

use App\Models\Otc;
use App\Models\Model;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\AccountCredit;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
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
    public static function boot() 
    {
        parent::boot();

        static::addGlobalScope('orderByCustomerFullName', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $orderBy = 'asc';
            $builder->join('customers', 'customers.id', '=', 'accounts.customer_id')
                    ->orderBy('customers.last_name', $orderBy)
                    ->orderBy('customers.first_name', $orderBy)
                    ->select('accounts.*'); // Ensure only Account fields are selected
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function accountCredits()
    {
        return $this->hasMany(AccountCredit::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

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
    public function scopeNotDisconnected($query)
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', '!=', 3); // Disconnected
        });
    }

    // Scope method to filter customers with remaining credits > 0
    public function scopeHasRemainingCredits($query)
    {
        return $query->whereHas('accountCredits', function ($query) {
            $query->where('amount', '>', 0);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the customer's remaining credits.
     *
     * @return float
     */
    public function getRemainingCreditsAttribute()
    {
        $result = $this->accountCredits()
            ->select(DB::raw('SUM(amount) as total_credits'))
            ->first();

        return $result && $result->total_credits !== null ? $result->total_credits : 0;
    }

    /**
     * Get the latest update date of the customer's credits.
     *
     * @return string
     */
    public function getCreditsLatestUpdatedAttribute()
    {
        $result = $this->accountCredits()
            ->select(DB::raw('MAX(created_at) as latest_created_at'))
            ->first();

        return $result ? $result->latest_created_at : null;
    }

    public function getDetailsAttribute()
    {
        $name = $this->customer->fullName;
        $subscription = $this->subscription->name;
        $location = $this->plannedApplication->location->name;

        return $name .': ' . $subscription .' - ' . $location;
    }


    // I put this accessor here instead in OTC model because this is many records, i pluck it.
    // OTC display in view or column
    public function getOtcDetailsAttribute()
    {
        $temp = $this->otcs->pluck('amountName')->toArray();

        return implode('<br>', $temp);
    }

    // I put this accessor here instead in contract period model because this is many records, i pluck it.
    // Contract Period display in view or column
    public function getContractPeriodDetailsAttribute()
    {
        $temp = $this->contractPeriods->pluck('name')->toArray();

        return implode('<br>', $temp);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
