<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Account;
use App\Models\BillingType;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Models\Scopes\ExcludeSoftDeletedAccountsScope;

class Billing extends Model
{
    use CurrencyFormat;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'billings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    protected $casts = [
        'particulars' => 'array',
    ];

    protected $attributes = [
        'billing_status_id' => 2, // Newly created bill default value 2 or Unpaid
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new ExcludeSoftDeletedAccountsScope);

        static::creating(function ($billing) {

            $billing->createParticulars();

        });
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getTotalAttribute()
    {
        $totalAmount = collect($this->particulars)->sum(function ($item) {
            return (float) $item['amount'];
        });

        return $this->currencyRound($totalAmount);
    }

    public function getBillingPeriodDetailsAttribute()
    {

        if ($this->billing_type_id == 1) {
            return "<strong>{$this->billingType->name}</strong>";
        }

        // return $this->date_start . ' - '. $this->date_end;

        return "
            <strong>Date Start</strong> : {$this->date_start} <br>
            <strong>Date End</strong> : {$this->date_end} <br>
            <strong>Cut Off</strong> : {$this->date_cut_off} <br>
        ";
    }

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


    public function getMonthlyRateAttribute()
    {
        return $this->account->monthlyRate;
    }

    public function getDailyRateAttribute()
    {
        if ($this->monthlyRate && $this->totalNumberOfDays) {
            return $this->monthlyRate / $this->totalNumberOfDays;
        }

        return;
    }

    public function getHourlyRateAttribute()
    {
        if ($this->dailyRate){
            return $this->dailyRate / 24; // 24 hours
        }

        return;
    }

    // Total number of days for the period
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

    public function getIsProRatedMonthlyAttribute()
    {
        if ($this->account->installed_date > $this->date_start) {
            return true;
        }   

        return false;
    }

    // pro rated total
    public function getProRatedServiceTotalAmountAttribute()
    {
        if ($this->isProratedMonthly) {
            $total = 0;

            $total += $this->dailyRate * $this->proRatedDaysAndHoursService['days'];
            $total += $this->hourlyRate * $this->proRatedDaysAndHoursService['hours'];
            
            return $this->currencyRound($total);
        }

        return;
    }

    public function getProRatedDaysAndHoursServiceAttribute()
    {
        if ($this->isProRatedMonthly) {
            if ($this->account->installed_date && $this->date_end) {
                return $this->proRatedDaysAndHoursService($this->account->installed_date, $this->date_end);
            }
        }
        
        return;
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
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function createParticulars()
    {
        $particulars = [];

        // Setting date fields to null based on billing_type_id
        if ($this->billing_type_id == 1) { // installment
            $this->date_start = null;
            $this->date_end = null;
            $this->date_cut_off = null;

            // OTCS
            if ($this->account->otcs) {
                foreach ($this->account->otcs as $otc) {
                    $particulars[] = [
                        'description' => $otc->name,
                        'amount' => $otc->amount,
                    ];
                }
            }

            // Contract Periods
            $contractPeriodExists = $this->account->contractPeriods()->where('contract_periods.id', 1)->exists();

            if ($contractPeriodExists) {
                $contractPeriod = $this->account->contractPeriods()->where('contract_periods.id', 1)->first();
                $particulars[] = [
                    'description' => $contractPeriod->name,
                    'amount' => $this->account->plannedApplication->price,
                ];
            }


        } elseif ($this->billing_type_id == 2) { // monthly
            $particulars[] = [
                'description' => $this->billingType->name,
                'amount' => $this->account->monthlyRate,
            ];

            // Pro-rated Service Adjustment
            if ($this->isProRatedMonthly) {

                $less = $this->totalNumberOfDays - $this->proRatedDaysAndHoursService['days'];
                $days = $less > 1 ? 'days' : 'day';

                $particulars[] = [
                    'description' => "Pro-rated Service Adjustment ($less $days)",
                    'amount' => -($this->account->monthlyRate - $this->proRatedServiceTotalAmount),
                ];
            }

            // TODO:: Don't forget to compute service interruption
        }

        // debug($particulars);

        $this->particulars = array_values($particulars);
    }
    
}
