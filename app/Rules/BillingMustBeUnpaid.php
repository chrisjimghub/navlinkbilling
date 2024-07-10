<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Contracts\Validation\ValidationRule;

class BillingMustBeUnpaid implements ValidationRule
{
    protected $billingId;

    public function __construct($billingId)
    {
        $this->billingId = $billingId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $billing = Billing::find($this->billingId);

        if (!$billing) {
            $fail(__('The selected billing item does not exist.'));
        }else {
            if ($billing->isPaid()) {
                $fail($this->message());
            }
        }
    }

    public function message()
    {
        return 'Invalid action. This bill has already been paid.';
    }
}
