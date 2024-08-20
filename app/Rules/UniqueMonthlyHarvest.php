<?php

namespace App\Rules;

use Closure;
use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueMonthlyHarvest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ignoreId = null;
        $accountId = request()->account_id;
        $date = request()->date_start;

        if (request()->isMethod('PUT')) {
            $ignoreId = request()->id ?? null;
        }

        $date = Carbon::parse($date); 
        $month = $date->month;
        $year = $date->year;

        //1. Check for existing record with the same account_id in the same month/year
        $query = Billing::where('account_id', $accountId)
            ->whereMonth('date_start', $month)
            ->whereYear('date_start', $year);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $existsForMonthYear = $query->exists();

        if ($existsForMonthYear) {
            $fail('This account already has a harvest record for '.$date->format('F').'.');
            return;
        }


        //2. Check if there's any unharvested record
        $query = Billing::where('account_id', $accountId)->unharvested();

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $unharvestedExists = $query->exists();

        if ($unharvestedExists) {
            $fail('This account has an unharvested record.');
        }
    }

}
