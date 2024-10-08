<?php

namespace App\Listeners;

use App\Events\GenerateBillEvent;
use App\Models\Account;
use App\Models\Billing;
use App\Events\BillProcessed;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Admin\Traits\BillingPeriod;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class BillEventSubscriber
{
    use CurrencyFormat;
    use BillingPeriod;

    protected $billing;

    protected $particulars;

    public function handleBillProcessed(BillProcessed $event): void
    {
        if ($event->billing instanceof Collection) {
            // If its a collection of records
            foreach ($event->billing as $billing) {
                $this->processed($billing);
            }
        } elseif ($event->billing instanceof Billing) {
            // 
            $this->processed($event->billing);
        }
        
    }

    public function handleGenerateBillEvent(GenerateBillEvent $event): void
    {
        foreach ($event->accounts as $account) {
            // make sure generate or create only bill if the account has no current unpaid bill with a type of monthly
            $hasBill = $account->billings()->where(function ($query) {
                $query->unpaid();
                $query->whereIn('billing_type_id', [2,4]); // monthly/advance 
            });

            // debug($hasBill->get()->toArray());

            if (!$hasBill->exists()) {
                // No unpaid monthly billings found, proceed to create a new billing
                $group = $account->billingGrouping;
                $period = $this->billingPeriod($group);

                // check this attribute for duplicates
                $attributes = [
                    'account_id' => $account->id,
                    'date_start' => $period['date_start'],
                    'date_end' => $period['date_end'],
                    'date_cut_off' => $period['date_cut_off'],
                ];

                // We use firstOrCreate to avoid duplicate if ever it has same billing period.
                // also this will trigger the dispatch property in billing and run the BillProcessed event.
                Billing::firstOrCreate($attributes, [
                    'billing_type_id' => 2, // monthly
                ]);
            }
        }
    }

    // 1 run of this = 1 Billing process
    public function processed($billing)
    {
        $this->billing = $billing;

        $this->particulars = [];

        // if instance of billing has particulars, then it must be edit, assign it to property particulars,
        // dont worry about it to be duplicated because the addOrUdateParticular method will add or update it if duplicate
        if ($this->billing->particulars) {
            $this->particulars = $this->billing->particulars;
        }

        $this->snapshot();
                
        if ($this->billing->isInstallmentFee()) {
            // installment 
            $this->processInstallment();
        }elseif ($this->billing->isMonthlyFee()) {
            // monthly
            $this->processMonthly();
        }elseif ($this->billing->isHarvestPisoWifi()) {
            // harvest
            $this->processHarvestWifi();
        }

        $this->billing->particulars = $this->particulars;
        $this->billing->saveQuietly();
    }

    public function processHarvestWifi()
    {
        // the default for billing_type_id is 2 or unpaid, let' just put it here so its created it will assigned as unharvested
        // and dont put if statement here, because its not necessary it we will just updated this to unharvested everytime this is touch
        // anyway when this item is marked as harvested it will use the saveQuietly so this will not run.
        $this->billing->markAsUnharvested();

        if (!$this->billing->date_start) {
            $this->billing->date_start = date('Y-m-d');
        }

        if (empty($this->particulars)) {
            $this->addOrUpdateParticular([
                'description' => __('app.wifi_harvest.gross_income'),
                'amount' => null,
            ]);
            
            $this->addOrUpdateParticular([
                'description' => __('app.wifi_harvest.internet_fee'),
                'amount' => $this->billing->monthly_rate,
            ]);

            $this->addOrUpdateParticular([
                'description' => __('app.wifi_harvest.electric_bill'),
                'amount' => null,
            ]);

            $this->addOrUpdateParticular([
                'description' => __('app.wifi_harvest.lessor'),
                'amount' => null,
            ]);
        }
    }

    public function processInstallment()
    {
        $this->billing->date_start = null;
        $this->billing->date_end = null;
        $this->billing->date_cut_off = null;

        // OTCS
        foreach ($this->billing->account->otcs as $otc) {
            $this->addOrUpdateParticular([
                'description' => ucwords($otc->name),
                'amount' => (float) $otc->amount,
            ]);
        }
        
        // Contract Periods
        $contractId = 1; // 1-month advance
        $contractPeriodExists = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->exists();

        if ($contractPeriodExists) {
            $contractPeriod = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->first();
            $this->addOrUpdateParticular([
                'description' => ucwords($contractPeriod->name),
                'amount' => (float) $this->billing->account->plannedApplication->price,
            ]);
        }
    }

    public function processMonthly()
    {
        // Check if a particular description contains (n Days) or Pro-rated and unset it if it does. This is because Pro-rated items
        // need computation and to avoid duplicates. For example, "Service Interruptions (2 Days)" cannot be compared in the 
        // addOrUpdateParticular method due to the varying labels caused by the integer beside the day text. Therefore, we remove it here.
        // It will be computed down below and re-added.
        $this->removeItemsWithDayPatternAndProRated();

        if (empty($this->billing->date_start) || empty($this->billing->date_end) || empty($this->billing->date_cut_off)) {
            $group = $this->billing->account->billingGrouping;
            $period = $this->billingPeriod($group);
            $this->billing->date_start = $period['date_start'];
            $this->billing->date_end = $period['date_end'];
            $this->billing->date_cut_off = $period['date_cut_off'];
        }

        // if not empty before_account_snapshot = No Upgrade Planned Application
        if (!$this->billing->before_account_snapshot) {
            $this->addOrUpdateParticular([
                'description' => ucwords($this->billing->billingType->name),
                'amount' => $this->billing->monthly_rate,
            ]);
    
            // Pro-rated Service Adjustment
            if ($this->billing->isProRatedMonthly()) {
                $amountAdjustment = $this->billing->daily_rate * $this->billing->pro_rated_non_service_days; 
                $this->addOrUpdateParticular([
                    'description' => ucwords($this->billing->pro_rated_desc),
                    'amount' => -($this->currencyRound($amountAdjustment)),
                ]);
    
            }
    
            // Service Interrptions
            $totalInterruptionDays = $this->billing->total_days_service_interruptions;
            if ($totalInterruptionDays) {
                $this->addOrUpdateParticular([
                    'description' => ucwords($this->billing->service_interrupt_desc),
                    'amount' => -($this->currencyRound($totalInterruptionDays * $this->billing->daily_rate)),
                ]);
            }
        }else {
            // Compute Upgrade Planned Application
            
            // remove Monthly Fee in pro rated upgrade if exist
            $this->particulars = array_filter($this->particulars, function($item) {
                return strtolower($item['description']) !== strtolower($this->billing->billingType->name);
            });
            
            // Re-index array to maintain numeric keys
            $this->particulars = array_values($this->particulars);


            // no need to negate the value we wont do it as deductions, bec. since we have 2 monthly fee: the prev and new, we wont do
            // the same as the normal Pro-rated, the normal is we put the monthly fee and then add the prorated deductions. but since
            // this have 2 monthly fee the new and prev. we just add it as positive and dont display or add monthly fee in particulars.
            
            // before
            $this->addOrUpdateParticular([
                'description' => ucwords($this->billing->before_upgrade_desc),
                'amount' => $this->currencyRound($this->billing->before_upgrade_daily_rate * $this->billing->before_upgrade_service_days),
            ]);

            // new
            $this->addOrUpdateParticular([
                'description' => ucwords($this->billing->new_upgrade_desc),
                'amount' => $this->currencyRound($this->billing->daily_rate * $this->billing->new_upgrade_service_days),
            ]);

            // service interruptions
            $totalInterruptionDays = $this->billing->upgrade_total_days_service_interruptions;

            // before
            if ($totalInterruptionDays['total_before'] > 0) {
                $this->addOrUpdateParticular([
                    'description' => ucwords($this->billing->before_service_interrupt_desc),
                    'amount' => -($this->currencyRound($totalInterruptionDays['total_before'] * $this->billing->before_upgrade_daily_rate)),
                ]);
            }
            
            // new
            if ($totalInterruptionDays['total_new'] > 0) {
                $this->addOrUpdateParticular([
                    'description' => ucwords($this->billing->new_service_interrupt_desc),
                    'amount' => -($this->currencyRound($totalInterruptionDays['total_new'] * $this->billing->daily_rate)),
                ]);
            }

        } // end - Compute Upgrade Planned Application
        
    }

    public function snapshot($column = 'account_snapshot')
    {
        $snapshot = [];

        $snapshot['account'] = $this->billing->account->toArray();
        $snapshot['plannedApplication'] = $this->billing->account->plannedApplication->toArray();
        $snapshot['plannedApplicationType'] = $this->billing->account->plannedApplication->plannedApplicationType->toArray();
        $snapshot['location'] = $this->billing->account->plannedApplication->location->toArray();
        $snapshot['subscription'] = $this->billing->account->subscription->toArray();
        $snapshot['otcs'] = $this->billing->account->otcs->toArray();
        $snapshot['contractPeriods'] = $this->billing->account->contractPeriods->toArray();
        $snapshot['accountStatus'] = $this->billing->account->accountStatus->toArray();
        $snapshot['accountCredits'] = $this->billing->account->remaining_credits ?? 0;
        
        $this->billing->{$column} = $snapshot;

        $this->billing->saveQuietly();
    }

    public function addOrUpdateParticular($newItem) {
        // Check if description already exists
        $found = false;
        foreach ($this->particulars as &$item) {
            if (strtolower($item['description']) === strtolower($newItem['description'])) {
                $item['amount'] = $newItem['amount']; // Modify the referenced element
                $found = true;
                break;
            }
        }
        unset($item); // Break the reference to avoid unexpected behavior

        // If description not found, add new item
        if (!$found) {
            $this->particulars[] = $newItem;
        }
    }
    
    /**
     * Remove items from the particulars array that contain the day pattern and optionally "Pro-rated".
     */
    public function removeItemsWithDayPatternAndProRated()
    {
        foreach ($this->particulars as $key => $item) {
            if (isset($item['description']) && containsDayPatternAndProRated($item['description'])) {
                unset($this->particulars[$key]);
            }
        }

        // Re-index the array to maintain numeric keys in sequence
        $this->particulars = array_values($this->particulars);
    }

}
