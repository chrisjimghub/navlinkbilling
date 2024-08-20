<?php

namespace   App\Http\Controllers\Admin\Traits;

use App\Models\Billing;
use Illuminate\Support\Str;
use App\Models\AccountCredit;

trait AdvancePayment
{
    public function advancePayment(Billing $billing)
    {
        // Find the label for one month advancem ID = 1 = 1 Month advance
        // NOTE:: this is just taking the label of id 1 in contract so it's not neccessary to use snapshots
        $oneMonthAdvanceLabel = $billing->account->contractPeriods()->where('contract_periods.id', 1)->first(); 

        if ($oneMonthAdvanceLabel) {
            // Create account credit for relevant particulars
            foreach ($billing->particulars as $particular) {
                if (Str::contains(strtolower($particular['description']), strtolower($oneMonthAdvanceLabel->name))) {
                    // if label/name = name of ID=1 then deposit as credit
                    AccountCredit::create([
                        'account_id' => $billing->account_id,
                        'amount' => $particular['amount'],
                    ]);
                }
                
                // if label/name = Deposit Account Credit then deposit as credit
                if (Str::contains(strtolower($particular['description']), strtolower("Deposit Account Credit"))) {
                    AccountCredit::create([
                        'account_id' => $billing->account_id,
                        'amount' => $particular['amount'],
                    ]);
                }
            }
        }
    }
}
