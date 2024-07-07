<?php

namespace App\Listeners;

use App\Events\BillProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InstallmentFeeProcessed
{
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
        // info('InstallmentFeeProcessed: '.$event->billing->id);
        $this->billing = $event->billing;

        // if billing is not installment fee then dont do anything
        if (!$this->billing->isInstallmentFee()) {
            return;
        }

        $particulars = [];

        $this->billing->date_start = null;
        $this->billing->date_end = null;
        $this->billing->date_cut_off = null;

        // OTCS
        if ($this->billing->account->otcs) {
            foreach ($this->billing->account->otcs as $otc) {
                $particulars[] = [
                    'description' => $otc->name,
                    'amount' => $otc->amount,
                ];
            }
        }
        
        // Contract Periods
        $contractId = 1; // 1-month advance
        $contractPeriodExists = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->exists();

        if ($contractPeriodExists) {
            $contractPeriod = $this->billing->account->contractPeriods()->where('contract_periods.id', $contractId)->first();
            $particulars[] = [
                'description' => $contractPeriod->name,
                'amount' => $this->billing->account->plannedApplication->price,
            ];
        }

        $this->billing->particulars = array_values($particulars);

        $this->billing->save();
    }

}
