<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParticularsRepeatField implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $particulars = json_decode($value);

        foreach ($particulars as $particular) {
            $desc = $particular->description;

            if (!$desc){
                $fail(__('app.billing_particulars_description_required'));
            }
            
            // make sure it accept 0
            if (is_null($particular->amount) || $particular->amount === '') {
                $fail(__('app.billing_particulars_amount_required'));
            }

            // Check if the description contains "Advance Payment" or its variations
            if (containsAdvancePayment($desc)) {
                // Now apply the detailed validation for "Advance Payment"
                if (!validParticularsAdvancePayment($desc)) {
                    $fail(__('app.billing_particulars_month_required'));
                }
            }
        }

    }
}
