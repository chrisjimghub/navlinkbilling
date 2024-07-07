<?php

namespace App\Listeners;

use App\Events\BillProcessed;
use App\Events\BillReprocessed;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillEventSubscriber
{
    use CurrencyFormat;

    protected $billing;

    protected $particulars = [];

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
        $this->billing = $event->billing;

        $this->snapshot();

        if ($this->billing->isInstallmentFee()) {
            // installment 
            $this->processInstallment();
        }elseif ($this->billing->isMonthlyFee()) {
            // monthly
            $this->processMonthly();
        }

        $this->billing->particulars = $this->particulars;
        $this->billing->save();
    }

    public function processInstallment()
    {
        $this->billing->date_start = null;
        $this->billing->date_end = null;
        $this->billing->date_cut_off = null;

        // OTCS
        foreach ($this->billing->account->otcs as $otc) {
            $this->particulars[] = [
                'description' => $otc->name,
                'amount' => $otc->amount,
            ];
        }
        
        // Contract Periods
        $contractId = 1; // 1-month advance
        $contractPeriodExists = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->exists();

        if ($contractPeriodExists) {
            $contractPeriod = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->first();
            $this->particulars[] = [
                'description' => $contractPeriod->name,
                'amount' => $this->billing->account->plannedApplication->price,
            ];
        }
    }

    public function processMonthly()
    {
        $this->particulars[] = [
            'description' => $this->billing->billingType->name,
            'amount' => $this->billing->account->monthly_Rate,
        ];

        // Pro-rated Service Adjustment
        if ($this->billing->isProRatedMonthly()) {
            $this->particulars[] = [
                'description' => $this->billing->pro_rated_desc,
                'amount' => -($this->billing->account->monthly_rate - $this->billing->pro_rated_service_total_amount),
            ];

        }

        // Service Interrptions
        $totalInterruptionDays = $this->billing->total_days_service_interruptions;
        if ($totalInterruptionDays) {
            $this->particulars[] = [
                'description' => $this->billing->service_interrupt_desc,
                'amount' => -($this->currencyRound($totalInterruptionDays * $this->billing->daily_rate)),
            ];
        }
    }

    public function snapshot()
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

        // TODO:: make sure when we have a button pay using credits, it will add a -amount row in account credits first, before updating the accountCredits here in snapshot
        $snapshot['accountCredits'] = $this->billing->account->remaining_credits ?? 0;
        
        $this->billing->account_snapshot = $snapshot;

        $this->billing->save();
    }










    // NOTE:: i think this is no longer needed because laravel read the method that start with handle
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    // public function subscribe(Dispatcher $events): array
    // {
    //     return [
    //         BillProcessed::class => 'handleBillProcessed',
    //         BillReprocessed::class => 'handleBillReprocessed',
    //     ];
    // }
}
