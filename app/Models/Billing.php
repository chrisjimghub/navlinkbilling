<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Account;
use App\Models\BillingType;
use App\Events\BillProcessed;
use App\Models\BillingStatus;
use App\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Admin\Traits\AccountCrud;
use App\Models\Traits\LocalScopes\ScopeDateOverlap;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\OwnedByAuthenticatedCustomerScope;

#[ScopedBy([OwnedByAuthenticatedCustomerScope::class])]
class Billing extends Model
{
    use CurrencyFormat;
    use AccountCrud;
    use ScopeDateOverlap;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $dispatchesEvents = [
        'created' => BillProcessed::class,
        'updated' => BillProcessed::class,
    ];

    protected $table = 'billings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    protected $casts = [
        'particulars' => 'array',
        'account_snapshot' => 'array',
        'before_account_snapshot' => 'array',
    ];

    protected $attributes = [
        'billing_status_id' => 2, // Newly created bill default value 2 or Unpaid
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function isProRatedMonthly() : bool
    {
        if ($this->account_installed_date > $this->date_start) {
            return true;
        }   

        return false;
    }

    public function isCutOff() : bool
    {
        $dateCutOff = Carbon::parse($this->date_cut_off);
        return $dateCutOff->isPast() || $dateCutOff->isToday();
    }

    public function isPaid() : bool
    {
        if ($this->billing_status_id == 1) {
            return true;
        }        

        return false;
    }

    public function isPending() : bool
    {
        if ($this->billing_status_id == 3) {
            return true;
        }        

        return false;
    }

    public function isUnpaid() : bool
    {
        if ($this->billing_status_id == 2) {
            return true;
        }        

        return false;
    }
    
    public function isHarvestPisoWifi() : bool
    {
        if ($this->billing_type_id == 3) {
            return true;
        }        

        return false;
    }

    public function isMonthlyFee() : bool
    {
        if ($this->billing_type_id == 2) {
            return true;
        }        

        return false;
    }

    public function isInstallmentFee() : bool
    {
        if ($this->billing_type_id == 1) {
            return true;
        }        

        return false;
    }
   
    public function accountServiceInterruptions()
    {
        return $this->account
            ->accountServiceInterruptions()
            ->whereBetween('date_start', [$this->date_start, $this->date_end])
            ->whereBetween('date_end', [$this->date_start, $this->date_end])
            ->get();
    }

    // before_service_interruptions
    public function beforeServiceInterruptions()
    {
        if ($this->before_account_snapshot) {
            return $this->account
                ->accountServiceInterruptions()
                ->whereBetween('date_start', [$this->date_start, $this->date_change])
                ->whereBetween('date_end', [$this->date_start, $this->date_change])
                ->get();
        }

        return;
    }

    // new_service_interruptions
    public function newServiceInterruptions()
    {
        if ($this->before_account_snapshot) {
            return $this->account
                ->accountServiceInterruptions()
                ->whereBetween('date_start', [$this->date_change, $this->date_end])
                ->whereBetween('date_end', [$this->date_change, $this->date_end])
                ->get();
        }

        return;
    }

    // NOTE:: This is only use in upgrade plan computations, where interruptions date range overlap the previous and new plan
    // overlap_service_interruptions
    public function overlapServiceInterruptions()
    {
        if ($this->before_account_snapshot) {
            return $this->account
                ->accountServiceInterruptions()
                // make sure the date interrupt is within the billing period.
                ->whereBetween('date_start', [$this->date_start, $this->date_end])
                ->whereBetween('date_end', [$this->date_start, $this->date_end])
                
                // below is where clause overlap    
                ->whereBetween('date_start', [$this->date_start, $this->date_change])
                ->whereBetween('date_end', [$this->date_change, $this->date_end])
                
                ->get();
        }

        return;

    }

    // Method to calculate days and hours difference
    public function proRatedDaysAndHoursService($dateStart = null, $dateEnd = null)
    {
        return dateDaysAndHoursDifference($dateStart, $dateEnd);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function billingStatus()
    {
        return $this->belongsTo(BillingStatus::class);
    }

    public function billingType()
    {
        return $this->belongsTo(BillingType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOwnByCustomer(Builder $query, $customerId)
    {
        return $query->whereHas('account', function (Builder $query) use ($customerId) {
            $query->where('customer_id', $customerId);
        });
    }

    public function scopeWithinBillingPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_start', [$startDate, $endDate])
                     ->whereBetween('date_end', [$startDate, $endDate]);
    }

    // accountFiber
    public function scopeAccountFiber($query)
    {
        return $query->where('account_snapshot->subscription->id', 2); // 2 = FIBER
    }

    // accountP2p
    public function scopeAccountP2p($query)
    {
        return $query->where('account_snapshot->subscription->id', 1); // 1 = P2P
    }

    public function scopeCutOffAccounts($query)
    {
        return $this->monthly()
        ->whereBetween('date_cut_off', [
            carbonToday()->subDays(5), 
            carbonToday()->addDays(5)
        ]);
    }

    public function scopeMonthly($query)
    {
        return $query->whereHas('billingType', function ($q) {
            $q->where('id', 2); // monthly 
        });
    }

    public function scopeInstallment($query)
    {
        return $query->whereHas('billingType', function ($q) {
            $q->where('id', 1); // installment 
        });
    }
    
    // this is different from scopeUnpaid, this can be pending or unpaid
    public function scopeNotPaid($query)
    {
        return $query->whereHas('billingStatus', function ($q) {
            $q->where('id','!=', 1); // not equal to paid
        });
    }

    public function scopePending($query)
    {
        return $query->whereHas('billingStatus', function ($q) {
            $q->where('id', 3); // Pending..
        });
    }

    public function scopeUnpaid($query)
    {
        return $query->whereHas('billingStatus', function ($q) {
            $q->where('id', 2); // Unpaid
        });
    }

    public function scopePaid($query)
    {
        return $query->whereHas('billingStatus', function ($q) {
            $q->where('id', 1);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    // mode_of_payment
    public function getModeOfPaymentAttribute()
    {
        return $this->paymentMethod->name ?? '';
    }

    // period
    public function getPeriodAttribute()
    {
        return Carbon::parse($this->date_start)->format(dateHumanReadable()) .' - '. Carbon::parse($this->date_end)->format(dateHumanReadable());
    }

    // year
    public function getYearAttribute()
    {
        return getYearFromDate($this->date_start);
    }

    // month
    public function getMonthAttribute()
    {
        return getMonthFromDate($this->date_start);
    }


    // account_installed_date
    public function getAccountInstalledDateAttribute()
    {
        if ($this->account_snapshot) {
            return Carbon::parse($this->account_snapshot['account']['installed_date']);
        }
        
        // we use this as backup, because this attribute are used even before the snapshot is saved. we need this.
        return $this->account->installed_date;
    }   
    
    // account_google_coordinates
    public function getAccountGoogleCoordiantesAttribute()
    {
        return $this->account_snapshot['account']['google_map_coordinates'];
    }

    // account_name
    public function getAccountNameAttribute()
    {
        return $this->account->customer->full_name;
    }

    // account_subscription_name
    public function getAccountSubscriptionNameAttribute()
    {
        return $this->account_snapshot['subscription']['name'];
    }

    // account_location_name
    public function getAccountLocationNameAttribute()
    {
        return $this->account_snapshot['location']['name'];
    }

    // account_planned_application_type_name
    public function getAccountPlannedApplicationTypeNameAttribute()
    {
        return $this->account_snapshot['plannedApplicationType']['name'];
    }

    // account_planned_application_type_name_shorten ex: Residential etc..
    public function getAccountPlannedApplicationTypeNameShortenAttribute()
    {
        $type = $this->account_planned_application_type_name;

        $type = explode("/", $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return trim($type);
    }

    // account_planned_application_mbps
    public function getAccountPlannedApplicationMbpsAttribute()
    {
        return $this->account_snapshot['plannedApplication']['mbps'];
    }

    // account_planned_application_price
    public function getAccountPlannedApplicationPriceAttribute()
    {
        return $this->currencyFormatAccessor($this->account_snapshot['plannedApplication']['price']);
    }

    // account_planned_application_details
    public function getAccountPlannedApplicationDetailsAttribute()
    {
        return $this->account_location_name . ' - '.
                $this->account_subscription_name . ', '. 
                $this->account_planned_application_type_name_shorten . ' : '. 
                $this->account_planned_application_mbps . 'Mbps --- '.
                $this->account_planned_application_price;
    }

    // account_details : Data Taken from snapshot
    public function getAccountDetailsAttribute()
    {
        $from = 'model_relationship';

        if ($this->account_snapshot) {
            $from = 'account_snapshot';
        }

        $data = 'total_number_of_days:'. $this->total_number_of_days;
        $data .= ' | ';
        $data .= 'daily_rate:'. $this->daily_rate;

        if ($this->before_account_snapshot) {
            $data .= ' | ';
            $data .= 'before_daily_rate:'. $this->before_upgrade_daily_rate;
        }

        return $this->accountDetails(
            from: $from,
            accountId: $this->account_id,
            name: $this->account_name,
            location: $this->account_location_name,
            type: $this->account_planned_application_type_name_shorten,
            subscription: $this->account_subscription_name, 
            mbps: $this->account_planned_application_mbps,
            installedDate: $this->account_installed_date->toDateString(),
            data: $data,
        );
    }

    // date_cut_off_badge
    public function getDateCutOffBadgeAttribute()
    {
        $dateCutOff = $this->date_cut_off;
        $class = '';
        $daysDifference = '';

        if ($dateCutOff) {
            // Calculate difference in days from now
            $cutOffDate = Carbon::parse($dateCutOff);
            $now = Carbon::now();
            $daysDifference = $now->diffInDays($cutOffDate);

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
                    Carbon::parse($this->date_cut_off)->format(dateHumanReadable()).
                '</span>'; // Return empty string if no condition matched
    }

    // total
    public function getTotalAttribute()
    {
        $totalAmount = collect($this->particulars)->sum(function ($item) {
            return (float) $item['amount'];
        });

        return $this->currencyRound($totalAmount);
    }

    // billing_period_details
    public function getBillingPeriodDetailsAttribute()
    {

        if ($this->isInstallmentFee()) {
            return "<strong>{$this->billingType->name}</strong>";
        }

        $appendDateChange = "";
        if ($this->before_account_snapshot) {
            $appendDateChange = "<strong>Date Change</strong> : {$this->date_change->toDateString()} <br>";
        }

        return "
            <strong>Date Start</strong> : {$this->date_start} <br>
            {$appendDateChange}
            <strong>Date End</strong> : {$this->date_end} <br>
            <strong>Cut Off</strong> : {$this->date_cut_off} <br>
        ";
    }

    public function getBillingPeriodDetailsExportAttribute()
    {

        if ($this->isInstallmentFee()) {
            return $this->billingType->name;
        }

        $appendDateChange = "";
        if ($this->before_account_snapshot) {
            $appendDateChange = "Date Change: {$this->date_change->toDateString()} \n";
        }

        return "
            Date Start: {$this->date_start} \n
            {$appendDateChange}
            Date End: {$this->date_end} \n
            Cut Off: {$this->date_cut_off} \n
        ";
    }

    // particular_details
    public function getParticularDetailsAttribute()
    {
        $details = [];

        if ($this->particulars) {
            foreach ($this->particulars as $particular) {
                $textColor = 'text-info ';
                $amount = $particular['amount'];
    
                if ($amount) {
                    
                    if ($amount < 0) {
                        $textColor = 'text-danger';
                    }

                    $amount = $this->currencyFormatAccessor($amount);
                }
    

                $details[] = "<strong>{$particular['description']}</strong> : <span class='{ $textColor }'>{$amount}</span>";
            }
            return implode('<br>', $details);
        }

        return;
    }

    // monthly_rate
    public function getMonthlyRateAttribute()
    {   
        return $this->account_snapshot['plannedApplication']['price'];
    }
    
    // daily_rate
    public function getDailyRateAttribute()
    {
        if ($this->monthly_rate && $this->total_number_of_days) {
            return $this->monthly_rate / $this->total_number_of_days;
        }

        return;
    }

    // before_upgrade_monthly_rate
    public function getBeforeUpgradeMonthlyRateAttribute()
    {
        if ($this->before_account_snapshot) {
            return $this->before_account_snapshot['plannedApplication']['price'];
        }

        return;
    }

    // before_upgrade_daily_rate
    public function getBeforeUpgradeDailyRateAttribute()
    {
        if ($this->before_account_snapshot) {
            return $this->before_upgrade_monthly_rate / $this->total_number_of_days;
        }

        return;
    }


    // total_number_of_days :Total number of days for the period
    public function getTotalNumberOfDaysAttribute()
    {
        if ($this->date_start && $this->date_end) {
            $startDate = Carbon::parse($this->date_start);
            $endDate = Carbon::parse($this->date_end);
        
            $totalNumberOfDays = $startDate->diffInDays($endDate);
        
            return $totalNumberOfDays;
        }

        return;
    }

    // pro_rated_service_total_amount : pro rated total
    public function getProRatedServiceTotalAmountAttribute()
    {
        if ($this->isProRatedMonthly()) {
            $total = $this->daily_rate * $this->pro_rated_days_and_hours_service['days'];
            return $this->currencyRound($total);
        }

        return;
    }

    // pro_rated_days_and_hours_service
    public function getProRatedDaysAndHoursServiceAttribute()
    {
        if ($this->isProRatedMonthly()) {
            $installedDate = $this->account_installed_date;
            if ($installedDate && $this->date_end) {
                return $this->proRatedDaysAndHoursService($installedDate, $this->date_end);
            }
        }
        
        return;
    }

    // pro_rated_desc
    public function getProRatedDescAttribute()
    {
        if ($this->isProRatedMonthly()) {
            $num = $this->pro_rated_non_service_days;
            $daysOrDay = $num > 1 ? 'days' : 'day';
    
            return "Pro-rated Service Adjustment ($num $daysOrDay)";
        }

        return;
    }

    // pro_rated_non_service_days
    public function getProRatedNonServiceDaysAttribute()
    {
        return $this->total_number_of_days - $this->pro_rated_days_and_hours_service['days'];
    }

    // before_upgrade_service_days
    // NOTE:: This is the total number of service days 
    public function getBeforeUpgradeServiceDaysAttribute()
    {
        if ($this->before_account_snapshot) {
            $dateStart = $this->date_start;
            $dateEnd = $this->date_change;

            // if this true, then the account start later than the billing period start_date
            if ($this->account_installed_date > $this->date_start) {
                $dateStart = $this->account_installed_date;
            }

            return dateDaysAndHoursDifference($dateStart, $dateEnd)['days'];
        }

        return;
    }

    // new_upgrade_service_days
    // NOTE:: This is the total number of service days 
    public function getNewUpgradeServiceDaysAttribute()
    {
        if ($this->before_account_snapshot) {
            $dateStart = $this->date_change;
            $dateEnd = $this->date_end;

            // if this true, then the account start later than the billing period start_date
            if ($this->account_installed_date > $this->date_start) {
                $dateStart = $this->account_installed_date;
            }

            return dateDaysAndHoursDifference($dateStart, $dateEnd)['days'];
        }

        return;
    }

    // before_upgrade_desc
    public function getBeforeUpgradeDescAttribute()
    {
        if ($this->before_account_snapshot) {
            $num = $this->before_upgrade_service_days;
            $daysOrDay = $num > 1 ? 'days' : 'day';
    
            $mbps = $this->before_account_snapshot['plannedApplication']['mbps'];
            $price = $this->before_account_snapshot['plannedApplication']['price'];

            $append = 'Prev: ';
            $append .= $mbps.'Mbps';
            $append .= '---';
            $append .= $this->currencyFormatAccessor($price);

            return $append." Pro-rated ($num $daysOrDay)";
        }

        return;
    }

    // new_upgrade_desc
    public function getNewUpgradeDescAttribute()
    {
        if ($this->before_account_snapshot) {
            $num = $this->new_upgrade_service_days;
            $daysOrDay = $num > 1 ? 'days' : 'day';
    
            $mbps = $this->account_snapshot['plannedApplication']['mbps'];
            $price = $this->account_snapshot['plannedApplication']['price'];

            $append = 'New: ';
            $append .= $mbps.'Mbps';
            $append .= '---';
            $append .= $this->currencyFormatAccessor($price);

            return $append." Pro-rated ($num $daysOrDay)";
        }

        return;
    }

    // service_interrupt_desc
    public function getServiceInterruptDescAttribute()
    {
        $totalDaysInterrupt = $this->total_days_service_interruptions;
        
        if ($totalDaysInterrupt) {
            $days = $totalDaysInterrupt > 1 ? 'days' : 'day';

            return "Service Interruptions ($totalDaysInterrupt $days)";
        }

        return;
    }

    // before_service_interrupt_desc
    public function getBeforeServiceInterruptDescAttribute()
    {
        $totalBefore = $this->upgrade_total_days_service_interruptions['total_before'];

        if ($totalBefore) {
            $days = $totalBefore > 1 ? 'days' : 'day';

            return "Prev: Service Interruptions ($totalBefore $days)";
        }

        return;
    }

    // new_service_interrupt_desc
    public function getNewServiceInterruptDescAttribute()
    {
        $totalNew = $this->upgrade_total_days_service_interruptions['total_new'];

        if ($totalNew) {
            $days = $totalNew > 1 ? 'days' : 'day';

            return "New: Service Interruptions ($totalNew $days)";
        }

        return;
    }

    // total_days_service_interruptions
    public function getTotalDaysServiceInterruptionsAttribute()
    {
        $interruptions = $this->accountServiceInterruptions();
        
        if ($interruptions) {
            $totalDaysInterrupt = 0;
            foreach ($interruptions as $interrupt) {
                $dateStart = Carbon::parse($interrupt->date_start);
                $dateEnd = Carbon::parse($interrupt->date_end);

                $totalDaysInterrupt += $dateStart->diffInDays($dateEnd);

            }

            return $totalDaysInterrupt;
        }

        return;
    }
    
    // upgrade_total_days_service_interruptions
    public function getUpgradeTotalDaysServiceInterruptionsAttribute()
    {   
        $totalBefore = 0;
        $totalNew = 0;

        $before = $this->beforeServiceInterruptions();
        $new = $this->newServiceInterruptions();
        $overlap = $this->overlapServiceInterruptions();
        
        if ($before) {
            foreach ($before as $interrupt) {
                $dateStart = Carbon::parse($interrupt->date_start);
                $dateEnd = Carbon::parse($interrupt->date_end);

                $totalBefore += $dateStart->diffInDays($dateEnd);
            }
        }

        if ($new) {
            foreach ($new as $interrupt) {
                $dateStart = Carbon::parse($interrupt->date_start);
                $dateEnd = Carbon::parse($interrupt->date_end);

                $totalNew += $dateStart->diffInDays($dateEnd);
            }
        }

        if ($overlap) {
            foreach ($overlap as $interrupt) {
                $dateStart = Carbon::parse($interrupt->date_start);
                $dateChange = Carbon::parse($this->date_change);
                $dateEnd = Carbon::parse($interrupt->date_end);

                $totalBefore += $dateStart->diffInDays($dateChange);
                $totalNew += $dateChange->diffInDays($dateEnd);

            }
        }

        return [
            'total_before' => $totalBefore,
            'total_new' => $totalNew,
        ];
    }

    // date_change
    public function getDateChangeAttribute()
    {
        if ($this->before_account_snapshot) {
            return Carbon::parse($this->before_account_snapshot['date_change']);
        }

        return;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // Method to mark billing as paid
    public function markAsPaid()
    {
        $this->billing_status_id = 1; // Set to 1 (paid)

        // Optionally return $this for chaining methods
        return $this;
    }

    public function markAsPending()
    {
        $this->billing_status_id = 3; 

        return $this;
    }

    public function paymentMethodGcash()
    {
        $this->payment_method_id = 3; 

        return $this;
    }

    public function markAsUnharvested()
    {
        $this->billing_status_id = 5; 

        return $this;
    }
}
