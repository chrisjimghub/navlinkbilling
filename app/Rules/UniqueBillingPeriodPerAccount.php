<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueBillingPeriodPerAccount implements ValidationRule
{
    protected $accountId;
    protected $dateStart;
    protected $dateEnd;
    protected $ignoreId; // ID to ignore during validation

    public function __construct($accountId, $dateStart, $dateEnd, $ignoreId = null)
    {
        $this->accountId = $accountId;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Determine if validation should be applied based on request method
        $isCreating = request()->isMethod('post');
        $isUpdating = request()->isMethod('put') || request()->isMethod('patch');

        if ($isCreating || $isUpdating) {
            $query = Billing::where('account_id', $this->accountId)
                        ->where(function ($query) {
                            $query->whereRaw('date_start <= ?', [$this->dateEnd])
                                ->whereRaw('DATE_SUB(date_end, INTERVAL 1 DAY) >= ?', [$this->dateStart]);
                        });
            
            
            // debug($query->get()->toArray());

            if ($isUpdating && $this->ignoreId) {
                $query->where('id', '!=', $this->ignoreId);
            }

            $exists = $query->exists();

            // Fail validation if overlapping record found
            if ($exists) {
                $fail($this->message());
            }
        }
    }

    public function message()
    {
        return 'The billing period overlaps with an existing record for this account.';
    }
}
