<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class DateRangePicker implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Str::contains($value, '-')) {
            $fail(__("Invalid date range format for {$attribute}."));
            return;
        }

        $dates = explode('-', $value);

        // Ensure the date range contains exactly two dates
        if (count($dates) !== 2) {
            $fail(__("Invalid date range format for {$attribute}."));
            return; // Exit the function early
        }

        // Trim whitespace from each date
        $startDate = trim($dates[0]);
        $endDate = trim($dates[1]);

        // Parse dates with Carbon
        try {
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate);
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate);
        } catch (\Exception $e) {
            $fail(__("Invalid date format for {$attribute}."));
            return; // Exit the function early
        }

        // Ensure the start date is less than the end date
        if ($startDate->gt($endDate)) {
            $fail(__("The end date must be greater than or equal to the start date for {$attribute}."));
        }


    }
}
