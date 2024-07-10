<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Contracts\Validation\ValidationRule;

class BillingMustBeUnpaid implements ValidationRule
{
    protected $billing;

    public function __construct($billingId)
    {
        $this->billing = Billing::find($billingId);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->billing) {
            if ($this->billing->isPaid()) {
                $fail($this->message());
            }
        }
    }

    public function message()
    {
        return 'Invalid action. This bill has already been paid.';
    }
}
