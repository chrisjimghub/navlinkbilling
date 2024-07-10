<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class UpgradePlanValidDate implements ValidationRule
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
            $dateStart = Carbon::parse($this->billing->date_start);
            $dateEnd = Carbon::parse($this->billing->date_end);
            $dateChange = Carbon::parse($value);

            // fail validation if dateChange is not equal or in between the billing period
            if (!$dateChange->between($dateStart, $dateEnd, true)) {
                $fail(__('The date must be equal to or within the billing period.'));
            } 
        }
    }
}
