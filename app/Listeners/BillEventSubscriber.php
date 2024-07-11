<?php

namespace App\Listeners;

use App\Models\Billing;
use App\Events\BillProcessed;
use App\Models\AccountCredit;
use Illuminate\Support\Carbon;
use App\Events\BillReprocessed;
use Illuminate\Events\Dispatcher;
use App\Events\AccountCreditSnapshot;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UpgradeAccountBillProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class BillEventSubscriber
{
    use CurrencyFormat;

    protected $billing;

    protected $particulars;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
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


    // 1 run of this = 1 Billing process
    public function processed($billing)
    {
        $this->billing = $billing;

        $this->particulars = [];

        $this->snapshot();
                
        if ($this->billing->isInstallmentFee()) {
            // installment 
            $this->processInstallment();
        } elseif ($this->billing->isMonthlyFee()) {
            // monthly
            $this->processMonthly();
        }

        // debug($this->particulars);

        $this->billing->particulars = $this->particulars;
        $this->billing->saveQuietly();
    }

    public function processInstallment()
    {
        $this->billing->date_start = null;
        $this->billing->date_end = null;
        $this->billing->date_cut_off = null;

        // OTCS
        foreach ($this->billing->account->otcs as $otc) {
            $this->particulars[] = [
                'description' => ucwords($otc->name),
                'amount' => $otc->amount,
            ];
        }
        
        // Contract Periods
        $contractId = 1; // 1-month advance
        $contractPeriodExists = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->exists();

        if ($contractPeriodExists) {
            $contractPeriod = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->first();
            $this->particulars[] = [
                'description' => ucwords($contractPeriod->name),
                'amount' => $this->billing->account->plannedApplication->price,
            ];
        }
    }

    public function processMonthly()
    {
        // NOTE:: comment for now, TBD
        // if ($this->billing->account->isFiber()) {
        //     $this->billing->date_start = now()->startOfMonth()->toDateString();
        //     $this->billing->date_end = now()->endOfMonth()->toDateString();
        //     $this->billing->date_cut_off = now()->endOfMonth()->addDays(5)->toDateString();
        // } elseif ($this->billing->account->isP2P()) {
        //     $this->billing->date_start = now()->subMonth()->startOfMonth()->addDays(19)->toDateString();
        //     $this->billing->date_end = now()->startOfMonth()->addDays(19)->toDateString();
        //     $this->billing->date_cut_off = now()->startOfMonth()->addDays(24)->toDateString();
        // }

        // if empty before_account_snapshot = No Upgrade Planned Application
        if (!$this->billing->before_account_snapshot) {
            $this->particulars[] = [
                'description' => ucwords($this->billing->billingType->name),
                'amount' => $this->billing->monthly_rate,
            ];
    
            // Pro-rated Service Adjustment
            if ($this->billing->isProRatedMonthly()) {
                $amountAdjustment = $this->billing->daily_rate * $this->billing->pro_rated_non_service_days; 
    
                $this->particulars[] = [
                    'description' => ucwords($this->billing->pro_rated_desc),
                    'amount' => -($this->currencyRound($amountAdjustment)),
                ];
    
            }
    
            // Service Interrptions
            $totalInterruptionDays = $this->billing->total_days_service_interruptions;
            if ($totalInterruptionDays) {
                $this->particulars[] = [
                    'description' => ucwords($this->billing->service_interrupt_desc),
                    'amount' => -($this->currencyRound($totalInterruptionDays * $this->billing->daily_rate)),
                ];
            }
        }else {
            // Compute Upgrade Planned Application
            
            // no need to negate the value we wont do it as deductions, bec. since we have 2 monthly fee: the prev and new, we wont do
            // the same as the normal Pro-rated, the normal is we put the monthly fee and then add the prorated deductions. but since
            // this have 2 monthly fee the new and prev. we just add it as positive and dont display or add monthly fee in particulars.
            $this->particulars[] = [
                'description' => ucwords($this->billing->before_upgrade_desc),
                'amount' => $this->currencyRound($this->billing->before_upgrade_daily_rate * $this->billing->before_upgrade_service_days),
            ];
            
            $this->particulars[] = [
                'description' => ucwords($this->billing->new_upgrade_desc),
                'amount' => $this->currencyRound($this->billing->daily_rate * $this->billing->new_upgrade_service_days),
            ];


            // TODO:: service interruptions



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
    
}
