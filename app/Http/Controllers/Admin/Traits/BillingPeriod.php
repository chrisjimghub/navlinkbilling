<?php

namespace App\Http\Controllers\Admin\Traits;

use InvalidArgumentException;
use Illuminate\Support\Carbon;
use App\Models\BillingGrouping;

trait BillingPeriod
{
    public function billingPeriod(BillingGrouping $grouping, $currentDate = null)
    {
        if (!$currentDate) {
			$currentDate = Carbon::now();
		}else {
			$currentDate = Carbon::parse($currentDate);
		}

        $dateStart = '';
		$dateEnd = '';
		$dateCutOff = '';

		if ($grouping->billing_cycle_id == 1) { // Previous Month - Current Month
			
			$dateStart = $currentDate->copy();
			$dateStart->subMonthNoOverflow()->day( adjustDayWithinMonth(day: $grouping->day_start, currentDate: $dateStart) );

			$dateEnd = $currentDate->copy();
			$dateEnd->day( adjustDayWithinMonth(day: $grouping->day_end, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($grouping->day_cut_off);

		}elseif ($grouping->billing_cycle_id == 2) { // Current Month - Current Month
		
			$dateStart = $currentDate->copy();
			$dateStart->day( adjustDayWithinMonth(day: $grouping->day_start, currentDate: $dateStart) );
			
			$dateEnd = $currentDate->copy();
			$dateEnd->day( adjustDayWithinMonth(day: $grouping->day_end, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($grouping->day_cut_off);

		}elseif ($grouping->billing_cycle_id == 3) { // Current Month - Next Month

			$dateStart = $currentDate->copy();
			$dateStart->day( adjustDayWithinMonth(day: $grouping->day_start, currentDate: $dateStart) );

			$dateEnd = $currentDate->copy();
			$dateEnd->addMonthNoOverflow()->day( adjustDayWithinMonth(day: $grouping->day_end, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($grouping->day_cut_off);

		}else {
			throw new InvalidArgumentException("Whoops, invalid billing cycle.");
		}

		return [
			'billing_grouping' => $grouping->name,
			'billing_cycle' => $grouping->billingCycle->name,	
			'current_month' => $currentDate->copy()->format('F'),
			'current_date' => $currentDate->copy()->toDateString(),
			'day_start' => $grouping->day_start,
			'day_end' => $grouping->day_end,
			'day_cut_off' => $grouping->day_cut_off,
			'date_start' => $dateStart->toDateString(),
			'date_end' => $dateEnd->toDateString(),
			'date_cut_off' => $dateCutOff->toDateString(),
		];
    }   

    /* 
        function billingPeriod($billingPeriod, $dayStart, $dayEnd, $dayCutOff, $billingType, $currentDate = null) {
		if (!$currentDate) {
			$currentDate = Carbon::now();
		}else {
			$currentDate = Carbon::parse($currentDate);
		}

		$dateStart = '';
		$dateEnd = '';
		$dateCutOff = '';

		if ($billingPeriod == 'previous_month_current_month') {
			
			$dateStart = $currentDate->copy();
			$dateStart->subMonthNoOverflow()->day( adjustDayWithinMonth(day: $dayStart, currentDate: $dateStart) );

			$dateEnd = $currentDate->copy();
			$dateEnd->day( adjustDayWithinMonth(day: $dayEnd, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($dayCutOff);

		}elseif ($billingPeriod == 'current_month_current_month') {
		
			$dateStart = $currentDate->copy();
			$dateStart->day( adjustDayWithinMonth(day: $dayStart, currentDate: $dateStart) );
			
			$dateEnd = $currentDate->copy();
			$dateEnd->day( adjustDayWithinMonth(day: $dayEnd, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($dayCutOff);

		}elseif ($billingPeriod == 'current_month_next_month') {

			$dateStart = $currentDate->copy();
			$dateStart->day( adjustDayWithinMonth(day: $dayStart, currentDate: $dateStart) );

			$dateEnd = $currentDate->copy();
			$dateEnd->addMonthNoOverflow()->day( adjustDayWithinMonth(day: $dayEnd, currentDate: $dateEnd) );

			$dateCutOff = $dateEnd->copy()->addDays($dayCutOff);

		}else {
			throw new InvalidArgumentException("Setting::get('".$billingType."_billing_period') is invalid or not exist!");
		}

		return [
			'billing_type' => $billingType,
			'current_month' => $currentDate->copy()->format('F'),
			'current_date' => $currentDate->copy()->toDateString(),
			'period_in_text' => $billingPeriod,	
			'day_start' => $dayStart,
			'day_end' => $dayEnd,
			'day_cut_off' => $dayCutOff,
			'date_start' => $dateStart->toDateString(),
			'date_end' => $dateEnd->toDateString(),
			'date_cut_off' => $dateCutOff->toDateString(),
		];
    
    */
}
