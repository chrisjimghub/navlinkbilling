<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueMonthlyHarvest implements ValidationRule
{
    protected $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @param  int|null  $ignoreId
     * @return void
     */
    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = Carbon::now(); // Use current date
        $month = $date->month;
        $year = $date->year;

        // Check for existing record with the same account_id in the same month/year
        $query = Billing::where('account_id', $value)
            ->where(function ($query) use ($month, $year) {
                // where this block 
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('created_at', $month);
                    $q->whereYear('created_at', $year);
                })
                // or where this block
                ->orWhere(function ($query) {
                    // $query->
                });
            });

        // Exclude the record being updated from the check
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $exists = $query->exists();

        if ($exists) {
            $fail('This account already has a harvest record for the current month.');
        }
    }
}
