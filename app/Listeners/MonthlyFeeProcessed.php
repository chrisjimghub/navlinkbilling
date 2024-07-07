<?php

namespace App\Listeners;

use App\Events\BillProcessed;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MonthlyFeeProcessed
{
    use CurrencyFormat;

    protected $billing;

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
    public function handle(BillProcessed $event): void
    {
        // info('MonthlyFeeProcessed: '.$event->billing->id);

        $this->billing = $event->billing;

        // if billing is not monthly then dont do anything
        if (!$this->billing->isMonthlyFee()) {
            return;
        }

        $particulars = [];

        $particulars[] = [
            'description' => $this->billing->billingType->name,
            'amount' => $this->billing->account->monthly_Rate,
        ];

        // Pro-rated Service Adjustment
        if ($this->billing->isProRatedMonthly()) {
            $particulars[] = [
                'description' => $this->billing->pro_rated_desc,
                'amount' => -($this->billing->account->monthly_rate - $this->billing->pro_rated_service_total_amount),
            ];

        }

        // Service Interrptions
        $totalInterruptionDays = $this->billing->total_days_servce_interruptions;
        if ($totalInterruptionDays) {
            $particulars[] = [
                'description' => $this->billing->service_interrupt_desc,
                'amount' => -($this->currencyRound($totalInterruptionDays * $this->billing->daily_rate)),
            ];
        }


        $this->billing->particulars = array_values($particulars);

        $this->billing->save();
    }
}
