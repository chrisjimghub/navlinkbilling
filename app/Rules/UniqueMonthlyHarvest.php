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

        //1. Check for existing record with the same account_id in the same month/year
        $query = Billing::where('account_id', $value)
            ->whereMonth('date_start', $month)
            ->whereYear('date_start', $year);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $existsForMonthYear = $query->exists();

        if ($existsForMonthYear) {
            $fail('This account already has a harvest record for the current month.');
            return;
        }


        //2. Check if there's any unharvested record
        $query = Billing::where('account_id', $value)->unharvested();

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $unharvestedExists = $query->exists();

        if ($unharvestedExists) {
            $fail('This account has an unharvested record.');
        }
    }

}
