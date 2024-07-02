<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParticularsRepeatField implements ValidationRule
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
        //
        if(request()->isMethod('PUT')) {

            $particulars = json_decode(request()->particulars);

            foreach ($particulars as $particular) {

                if (!$particular->description) {
                    $fail(__('app.billing_particulars_description_required'));
                }
                
                // make sure it accept 0
                if (is_null($particular->amount) || $particular->amount === '') {
                    $fail(__('app.billing_particulars_amount_required'));
                }
                
            }
        }

        
    }
}
