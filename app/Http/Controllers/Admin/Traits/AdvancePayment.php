<?php

namespace   App\Http\Controllers\Admin\Traits;

use App\Models\Billing;
use Illuminate\Support\Str;
use App\Models\AccountCredit;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Admin\Traits\BillingPeriod;

trait AdvancePayment
{
    use BillingPeriod;
        
    public function advancePayment(Billing $billing)
    {
        // Find the label for one month advancem ID = 1 = 1 Month advance
        // NOTE:: this is just taking the label of id 1 in contract so it's not neccessary to use snapshots
        $oneMonthAdvanceLabel = $billing->account->contractPeriods()->where('contract_periods.id', 1)->first(); 

        $depositKeys = $this->advancePaymentKeys($oneMonthAdvanceLabel->name ?? null);

        foreach ($billing->particulars as $particular) {
            $desc = $particular['description'];
            // mostly use in installation, when having 1 month deposit
            foreach ($depositKeys as $key) {
                if ( Str::contains(strtolower($desc), strtolower($key)) ) {
                    AccountCredit::create([
                        'account_id' => $billing->account_id,
                        'amount' => $particular['amount'],
                    ]);

                    break;
                }
            }

            
            if ( containsAdvancePayment($desc) ) {
                if (validParticularsAdvancePayment($desc)) {
                    $dateString = extractMonthAndConvertToDate($desc);
                    $date = Carbon::createFromFormat('Y-m-d', $dateString);

                    // last bill date_end
                    $lastBill = Billing::billingCrud()->where('account_id', $billing->account_id)->latest()->first();

                    $lastBillMonth = null;
                    if ($lastBill) {
                        $lastBillMonth = $lastBill->date_end;
                    }else {
                        $lastBillMonth = $billing->date_end;
                    }

                    if ($date->month <= Carbon::parse($lastBillMonth)->month) {
                        $date->addYear();
                    }

                    $date = $date->format('Y-m-d');

                    $account = $billing->account;
                    $period = $this->billingPeriod($account->billingGrouping, $date);

                    // We use firstOrCreate to avoid duplicate if ever it has same billing period.
                    // also this will trigger the dispatch property in billing and run the BillProcessed event.
                    // we must make sure that the even is dispatch so snapshot the particulars.
                    $record = Billing::firstOrCreate([
                        'account_id' => $account->id,
                        'billing_type_id' => 4, // advance payment
                        'date_start' => $period['date_start'],
                        'date_end' => $period['date_end'],
                        'date_cut_off' => $period['date_cut_off'],
                    ]); 

                    $recordParticulars = [
                        'description' => $particular['description'],
                        'amount' => $particular['amount'],
                    ];
                    $record->particulars = [$recordParticulars]; // Must:: wrap in bracket bec. this column is json/casted in arrays

                    $record->payment_method_id = $billing->payment_method_id;
                    $record->markAsPaid();
                    $record->saveQuietly();
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
