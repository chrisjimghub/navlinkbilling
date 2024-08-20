<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAccountHarvest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $billing = Billing::find(request()->id);

        if ($billing->billing_type_id != 3) { // Piso Wifi
            $fail('Invalid account harvest type.');
        }

        if ($billing->billing_status_id != 5) { // Unharvested
            $fail('Invalid account harvest status.');
        }
    }
}
