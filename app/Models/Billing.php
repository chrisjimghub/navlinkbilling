<?php

namespace App\Models;

use App\Events\AccountCreditSnapshot;
use App\Events\BillProcessed;
use App\Models\Model;
use App\Models\Account;
use App\Models\BillingType;
use App\Models\BillingStatus;
use App\Models\Traits\LocalScopes\ScopeDateOverlap;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Admin\Traits\AccountCrud;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Models\Scopes\ExcludeSoftDeletedAccountsScope;

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
    
    // Method to mark billing as paid
    public function markAsPaid()
    {
        $this->billing_status_id = 1; // Set to 1 (paid)

        // Optionally return $this for chaining methods
        return $this;
    }

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

    public function isUnpaid() : bool
    {
        if ($this->billing_status_id == 2) {
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

    // Method to calculate days and hours difference
    public function proRatedDaysAndHoursService($dateStart = null, $dateEnd = null)
    {
        $dateStart = Carbon::parse($dateStart);
        $dateEnd = Carbon::parse($dateEnd);

        if ($dateStart && $dateEnd) {
            // Calculate the difference and format it
            $difference = $dateEnd->diff($dateStart)->format('%a|%H|%I');
            $diff = $dateEnd->diff($dateStart)->format('%a days, %H:%I');

            // Explode the formatted difference into an array
            list($days, $hours, $minutes) = explode('|', $difference);

            // Create the array with named keys
            return [
                'days' => (int) $days,
                'hours' => (int) $hours,
                'minutes' => (int) $minutes,
                'diff' => $diff,
            ];

        }

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
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

    // real_account
    public function getRealAccountAttribute() 
    {
        if ($this->account_snapshot) {
            return $this->account_snapshot;
        }

        return $this->account;
    }

    // account_installed_date
    public function getAccountInstalledDateAttribute()
    {
        if ($this->real_account) {
            return $this->real_account['account']['installed_date'];
        }
        
        // we use this as backup, because this attribute are used even before the snapshot is saved. we need this.
        return $this->account->installed_date;
    }   
    
    // account_google_coordinates
    public function getAccountGoogleCoordiantesAttribute()
    {
        return $this->real_account['account']['google_map_coordinates'];
    }

    // account_name
    public function getAccountNameAttribute()
    {
        return $this->account->customer->full_name;
    }

    // account_subscription_name
    public function getAccountSubscriptionNameAttribute()
    {
        return $this->real_account['subscription']['name'];
    }

    // account_location_name
    public function getAccountLocationNameAttribute()
    {
        return $this->real_account['location']['name'];
    }

    // account_planned_application_type_name
    public function getAccountPlannedApplicationTypeNameAttribute()
    {
        return $this->real_account['plannedApplicationType']['name'];
    }

    // account_planned_application_type_name_shorten ex: Residential etc..
    public function getAccountPlannedApplicationTypeNameShortenAttribute()
    {
        $type = $this->account_planned_application_type_name;

        $type = explode("/", $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return $type;
    }

    // account_planned_application_mbps
    public function getAccountPlannedApplicationMbpsAttribute()
    {
        return $this->real_account['plannedApplication']['mbps'];
    }

    // account_planned_application_price
    public function getAccountPlannedApplicationPriceAttribute()
    {
        return $this->currencyFormatAccessor($this->real_account['plannedApplication']['price']);
    }

    // account_planned_application_details
    public function getAccountPlannedApplicationDetailsAttribute()
    {
        return $this->account_location_name . ' - '. 
                $this->account_planned_application_type_name_shorten . ' :'. 
                $this->account_planned_application_mbps . 'Mbps ----- '.
                $this->account_planned_application_price;
    }

    // account_details : Data Taken from snapshot
    public function getAccountDetailsAttribute()
    {
        $from = 'model_relationship';

        if ($this->account_snapshot) {
            $from = 'account_snapshot';
        }

        return $this->accountDetails(
            from: $from,
            accountId: $this->account_id,
            name: $this->account_name,
            location: $this->account_location_name,
            type: $this->account_planned_application_type_name_shorten,
            subscription: $this->account_subscription_name, 
            mbps: $this->account_planned_application_mbps,
            installedDate: $this->account_installed_date,
            dailyRate: $this->daily_rate,
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

    // billing_period_detals
    public function getBillingPeriodDetailsAttribute()
    {

        if ($this->isInstallmentFee()) {
            return "<strong>{$this->billingType->name}</strong>";
        }

        // return $this->date_start . ' - '. $this->date_end;

        return "
            <strong>Date Start</strong> : {$this->date_start} <br>
            <strong>Date End</strong> : {$this->date_end} <br>
            <strong>Cut Off</strong> : {$this->date_cut_off} <br>
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
        return $this->real_account['plannedApplication']['price'];
    }
    
    // daily_rate
    public function getDailyRateAttribute()
    {
        if ($this->monthly_rate && $this->total_number_of_days) {
            return $this->monthly_rate / $this->total_number_of_days;
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
            $installedDate = $this->accountInstalledDate;
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
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
