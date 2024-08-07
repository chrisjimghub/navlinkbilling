<?php

namespace App\Rules;

use Closure;
use App\Models\Account;
use App\Models\Billing;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAccountBillingType implements ValidationRule
{
    protected $accountId;
    protected $billingTypeId;

    public function __construct($accountId, $billingTypeId)
    {
        $this->accountId = $accountId;
        $this->billingTypeId = $billingTypeId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dd('test');
        if ($this->billingTypeId == 1) { // installation fee
            // fire only on create 
            if (request()->isMethod('post')) {
                $exists = Billing::where('account_id', $this->accountId)
                    ->where('billing_type_id', $this->billingTypeId)
                    ->exists();

                if ($exists) {
                    $fail(__('app.billing_unique_account_billing_type_installation'));
                }
            }
            
        }elseif ($this->billingTypeId == 2) { // monthly fee
            // Run this validation in Create only, not in update/edit
            if (request()->isMethod('post')) {
                $exists = Billing::where('account_id', $this->accountId)
                    ->where('billing_type_id', $this->billingTypeId)
                    ->where('billing_status_id' , 2) // unpaid
                    ->exists();

                if ($exists) {
                    $fail(__('app.billing_unique_account_billing_type_monthly'));
                }

                // Check if the account has an installed date for pro-rated computations
                $exists = Account::where('accounts.id', $this->accountId)
                    ->whereNull('accounts.installed_date')
                    ->exists();

                if ($exists) {
                    $fail(__('app.billing_account_must_have_installed_date'));
                }

            }

        }else {
            // do nothing
        }
    }
}