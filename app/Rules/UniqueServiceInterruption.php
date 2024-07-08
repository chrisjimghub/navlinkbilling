<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\AccountServiceInterruption;

class UniqueServiceInterruption implements ValidationRule
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
            $query = AccountServiceInterruption::where('account_id', $this->accountId)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('date_start', '<=', $this->dateStart)
                            ->where('date_end', '>=', $this->dateStart);
                    })
                    ->orWhere(function ($q) {
                        $q->where('date_start', '<=', $this->dateEnd)
                            ->where('date_end', '>=', $this->dateEnd);
                    });
                });

            if ($isUpdating && $this->ignoreId) {
                $query->where('id', '!=', $this->ignoreId);
            }

            $existingInterruptions = $query->exists();

            // Fail validation if overlapping record found
            if ($existingInterruptions) {
                $fail($this->message());
            }
        }
    }


    public function message()
    {
        return 'The service interruption overlaps with an existing record for this account.';
    }
}
