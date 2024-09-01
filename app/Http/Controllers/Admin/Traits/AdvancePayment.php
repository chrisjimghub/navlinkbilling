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

        foreach ($billing->particulars as $particular) {
            foreach ($this->advancePaymentKeys($oneMonthAdvanceLabel->name) as $key) {
                if ( Str::contains(strtolower($particular['description']), strtolower($key)) ) {
                    AccountCredit::create([
                        'account_id' => $billing->account_id,
                        'amount' => $particular['amount'],
                    ]);

                    break;
                }
            }
        }//end foreach
    }

    // if you want to add more keys to save particulars description into accounts credits
    public function advancePaymentKeys($moreKeys = null)
    {
        $keys = [
            // 
        ];

        if ($moreKeys) {
            if (!is_array($moreKeys)) {
                $moreKeys = (array) $moreKeys;
            }

            $keys = array_merge($keys, $moreKeys);
        }

        return $keys;
    }

}
