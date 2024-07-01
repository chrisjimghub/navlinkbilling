<?php

namespace App\Rules;

use Closure;
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
        if ($this->billingTypeId == 1) { // installation fee
            $exists = Billing::where('account_id', $this->accountId)
                ->where('billing_type_id', $this->billingTypeId)
                ->exists();

            if ($exists) {
                $fail(__('app.billing_unique_account_billing_type_installation'));
            }
        }elseif ($this->billingTypeId == 2) { // monthly fee
            $exists = Billing::where('account_id', $this->accountId)
                ->where('billing_type_id', $this->billingTypeId)
                ->where('billing_status_id' , 2) // unpaid
                ->exists();

            if ($exists) {
                $fail(__('app.billing_unique_account_billing_type_monthly'));
            }
        }else {
            // do nothing
        }
    }
}