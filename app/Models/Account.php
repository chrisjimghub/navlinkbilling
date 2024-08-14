<?php

namespace App\Models;

use App\Models\Otc;
use App\Models\Model;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\AccountCredit;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use Illuminate\Support\Carbon;
use App\Models\BillingGrouping;
use App\Models\PisoWifiCollector;
use Illuminate\Support\Facades\DB;
use App\Models\AccountServiceInterruption;
use App\Http\Controllers\Admin\Traits\AccountCrud;

class Account extends Model
{
    use AccountCrud;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    
    // protected $dispatchesEvents = [
    //         NOTE:: I remove the event here, because i already dispatch the event 
    //         in Update in AccountCrudController so pivot table changes will also fire this event
    //     'updated' => BillProcessed::class, 
    // ];

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

    public function isFiber() : bool
    {
        if ($this->subscription->id == 2) {
            return true;
        }

        return false;
    }

    public function isP2P() : bool
    {
        if ($this->subscription->id == 1) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function pisoWifi()
    {
        return $this->hasOne(PisoWifiCollector::class);
    }

    public function billingGrouping()
    {
        return $this->belongsTo(BillingGrouping::class);
    }

    public function accountServiceInterruptions()
    {
        return $this->hasMany(AccountServiceInterruption::class);
    }

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
    public function scopeWithBillingGrouping($query, $id)
    {
        return $query->where('billing_grouping_id', $id);
    }

    public function scopeWithStatus($query, $statusId)
    {
        return $query->where('account_status_id', $statusId);
    }

    public function scopeWithSubscription($query, $statusId)
    {
        return $query->where('subscription_id', $statusId);
    }

    public function scopeP2p($query)
    {
        return $query->whereHas('subscription', function ($q) {
            $q->where('id', 1); 
        });
    }

    public function scopeFiber($query)
    {
        return $query->whereHas('subscription', function ($q) {
            $q->where('id', 2); 
        });
    }

    public function scopeAllowedBill($query)
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->whereIn('id', [1,2]);
            // 1. connected
            // 2. installing 
        })->installed();
                
    }

    public function scopeInstalled($query)
    {
        return $query->whereNotNull('installed_date');
    }

    public function scopeNotInstalled($query)
    {
        return $query->whereNull('installed_date')
                ->notDisconnected();
    }

    public function scopeNotDisconnected($query)
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', '!=', 3); // Disconnected
        });
    }

    public function scopeConnectedNoBilling($query) 
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', 4); // Connected - No Billing 
        });
    }

    public function scopeConnected($query) 
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', 1); // 
        });
    }

    public function scopeInstalling($query) 
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', 2); // 
        });
    }

    public function scopeDisconnected($query) 
    {
        return $query->whereHas('accountStatus', function ($q) {
            $q->where('id', 3); // 
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
    public function getCreatedBadgeAttribute()
    {
        $date = $this->created_at;
        $class = '';
        $daysDifference = '';

        if ($date) {
            // Calculate difference in days from now
            $tempDate = Carbon::parse($date);
            $now = Carbon::now();
            $daysDifference = $now->diffInDays($tempDate);

            // Determine badge class based on days difference
            if ($daysDifference <= 0) {
                $class = 'text-danger';
            }elseif ($daysDifference <= 2) {
                $class = 'text-warning'; 
            } elseif ($daysDifference <= 4) {
                $class = 'text-info'; 
            }
        }

        return '<span 
                    diff="'.$daysDifference.'"
                    class="'.$class.'">'.
                    Carbon::parse($date)->format(dateHumanReadable()).
                '</span>'; // Return empty string if no condition matched
    }

    public function getInstalledDateBadgeAttribute()
    {
        $dateInstalled = $this->installed_date;
        $class = '';
        $daysDifference = '';

        if ($dateInstalled) {
            // Calculate difference in days from now
            $tempDate = Carbon::parse($dateInstalled);
            $now = Carbon::now();
            $daysDifference = $now->diffInDays($tempDate);

            // Determine badge class based on days difference
            if ($daysDifference <= 0) {
                $class = 'text-danger';
            }elseif ($daysDifference <= 2) {
                $class = 'text-warning'; 
            } elseif ($daysDifference <= 4) {
                $class = 'text-info'; 
            }
        }

        return '<span 
                    diff="'.$daysDifference.'"
                    class="'.$class.'">'.
                    Carbon::parse($dateInstalled)->format(dateHumanReadable()).
                '</span>'; // Return empty string if no condition matched
    }

    // For DailyRate and Hourly Rate, i cant compute it without the date_start and date_end if Billing, so i put it in billing model instad
    public function getMonthlyRateAttribute()
    {
        return $this->plannedApplication->price ?? 0;
    }

    /**
     * Get the customer's remaining credits.
     *
     * @return float
     */
    // remaining_credits
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
        $name = $this->customer->fullName ?? '';
        $subscription = $this->subscription->name;
        $location = $this->plannedApplication->location->name;
        $type = $this->plannedApplication->plannedApplicationType->name;

        $type = explode("/", $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return $name .': ' . $subscription .' - ' . $location .', ' .$type;
    }

    public function getDetailsAllAttribute()
    {
        $name = $this->customer->fullName ?? '';
        $subscription = $this->subscription->name;
        $location = $this->plannedApplication->location->name;
        $type = $this->plannedApplication->plannedApplicationType->name;
        $plannedApp = $this->plannedApplication;

        $type = explode("/", $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return $this->accountDetails(
            from: 'account',
            id: $this->id,
            name: $name,
            location: $location,
            type: $type,
            subscription: $subscription, 
            mbps: $plannedApp->mbps,
            installedDate: $this->instaled_date
        );

        // return $name .': ' . $subscription .' - ' . $location;
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
