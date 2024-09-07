<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BankCheckRepeatField implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fields = json_decode($value);

        foreach ($fields as $field) {

            if (request()->input('paymentMethod') == 4) { // bank/check
                if (!$field->check_issued_date){
                    $fail(__('app.vouchers.validation.check_issued_date'));
                }

                if (!$field->check_number) {
                    $fail(__('app.vouchers.validation.check_number'));
                }
            }
        }
    }
}
