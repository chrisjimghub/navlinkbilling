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
        if ($this->billingTypeId == 2) {

            if(request()->isMethod('PUT')) {

                $particulars = json_decode(request()->particulars);
    
                foreach ($particulars as $particular) {
    
                    if (!$particular->description) {
                        $fail(__('app.billing_particulars_description_required'));
                    }
                    
                    if (!$particular->amount) {
                        $fail(__('app.billing_particulars_amount_required'));
                    }
                    
                }
            }

        }// end billingTypeId
        
    }
}
