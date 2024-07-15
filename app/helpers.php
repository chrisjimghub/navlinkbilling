<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Backpack\Settings\app\Models\Setting;

if (! function_exists('modelInstance')) {
	function modelInstance($class, $useFullPath = false) {
		if ($useFullPath) {
			return new $class;
		}

		// remove App\Models\ so i could have choice
		// to provide it in parameter
		$class = str_replace('App\\Models\\','', $class);

		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

// DATES
// get date of month using the day
if (! function_exists('dateOfMonth')) {
	function dateOfMonth($day, $returnAsCarbonInstance = false) {
		// Get the current month's starting date
		$startDate = Carbon::now()->startOfMonth();
	
		// Calculate the last day of the current month
		$lastDayOfMonth = $startDate->copy()->endOfMonth()->day;
	
		// Validate $day to ensure it's within valid range (1 - last day of the month)
		if ($day < 1 || $day > $lastDayOfMonth) {
			throw new InvalidArgumentException("Day must be between 1 and {$lastDayOfMonth}.");
		}
	
		// Calculate the target date
		$targetDate = $startDate->addDays($day - 1); // Subtract 1 because $day is 1-indexed
		
		if ($returnAsCarbonInstance) {
			return $targetDate;
		}

		return $targetDate->toDateString();
	}
}

if (! function_exists('dateOfNextMonth')) {
	function dateOfNextMonth($day, $returnAsCarbonInstance = false) {
		// Get the current month's starting date
		$startDate = Carbon::now()->addMonth()->startOfMonth();
	
		// Calculate the last day of the current month
		$lastDayOfMonth = $startDate->copy()->endOfMonth()->day;
	
		// Validate $day to ensure it's within valid range (1 - last day of the month)
		if ($day < 1 || $day > $lastDayOfMonth) {
			throw new InvalidArgumentException("Day must be between 1 and {$lastDayOfMonth}.");
		}
	
		// Calculate the target date
		$targetDate = $startDate->addDays($day - 1); // Subtract 1 because $day is 1-indexed
	
		if ($returnAsCarbonInstance) {
			return $targetDate;
		}

		return $targetDate->toDateString();
	}
}

// get date of prev month
if (! function_exists('dateOfPrevMonth')) {
	function dateOfPrevMonth($day, $returnAsCarbonInstance = false) {
		// Get the current month's starting date
		$startDate = Carbon::now()->subMonth()->startOfMonth();
	
		// Calculate the last day of the current month
		$lastDayOfMonth = $startDate->copy()->endOfMonth()->day;
	
		// Validate $day to ensure it's within valid range (1 - last day of the month)
		if ($day < 1 || $day > $lastDayOfMonth) {
			throw new InvalidArgumentException("Day must be between 1 and {$lastDayOfMonth}.");
		}
	
		// Calculate the target date
		$targetDate = $startDate->addDays($day - 1); // Subtract 1 because $day is 1-indexed
	
		if ($returnAsCarbonInstance) {
			return $targetDate;
		}

		return $targetDate->toDateString();
	}
}

if (! function_exists('dateDaysAndHoursDifference')) {
	function dateDaysAndHoursDifference($dateStart, $dateEnd) {
		$dateStart = Carbon::parse($dateStart);
        $dateEnd = Carbon::parse($dateEnd);

		// Calculate the difference and format it
		$difference = $dateEnd->diff($dateStart)->format('%a|%H|%I');
		$diff = $dateEnd->diff($dateStart)->format('%a days, %H:%I');

		// Explode the formatted difference into an array
		list($days, $hours, $minutes) = explode('|', $difference);

		// Create the array with named keys
		return [
			'days' => (int) $days,
			'hours' => (int) $hours,
			'minutes' => (int) $minutes,
			'diff' => $diff,
		];
	}
}

if (! function_exists('getYearFromDate')) {
	function getYearFromDate($date) {
        return Carbon::parse($date)->year;
	}
}

if (! function_exists('getMonthFromDate')) {
	function getMonthFromDate($date) {
        return Carbon::parse($date)->format('F');
	}
}


if (! function_exists('carbonInstance')) {
	function carbonInstance($date) {
        return Carbon::parse($date);
	}
}

if (! function_exists('carbonToday')) {
	function carbonToday() {
        return Carbon::today();
	}
}

if (! function_exists('carbonNow')) {
	function carbonNow() {
        return Carbon::now();
	}
}

if (! function_exists('dateHumanReadable')) {
	function dateHumanReadable() {
        return 'M j, Y';
	}
}

// this function make sure that the provided day is within month, if it exceed then it returns the last day of the month
if (! function_exists('adjustDayWithinMonth')) {
	function adjustDayWithinMonth($day, $month = null) {
		$currentDate = Carbon::now();
		
		if ($month !== null) {
			if ($month < 1 || $month > 12) {
				throw new InvalidArgumentException("Month must be between 1 and 12.");
			}
			$currentDate->month($month);
		}
		
		$lastDayOfMonth = $currentDate->endOfMonth()->day;
		
		if ($day > $lastDayOfMonth) {
			return $lastDayOfMonth;
		}
		
		return $day;
	}
}
// end DATES

// billing
// billingDateStart
if (! function_exists('billingDateStart')) {
	function billingDateStart($type) { // fiber or p2p
		$billingStart = Setting::get($type.'_billing_start');
		$day = (int) Setting::get($type.'_day_start'); // Get the day as an integer
	
		// Set the current date with the specified day
		$currentDate = Carbon::now()->day($day);

		if ($billingStart == 'previous_month') {
			// subMonthNoOverFlow = subtract a month and then retain the day, if day out of bounce then it will get the last day of month.
			// if you want subMonth and adjust it so that if it out of bounce it will add the day to the next month then ust subMonthOverflow
			$currentDate->subMonthNoOverflow(); 
		} elseif ($billingStart == 'current_month') {
			// No need to adjust, already in the current month
		} else {
			throw new InvalidArgumentException("Setting::get('".$type."_billing_start') is empty or invalid!");
		}
	
		return $currentDate->toDateString();
	}
}

// TODO:: BRB
if (! function_exists('billingDateEnd')) {
	function billingDateEnd($type) { // fiber or p2p
		$billingStart = Setting::get($type.'_billing_start'); // fiber_billing_start/p2p_billing_start = previous_month/current_month
		$day = (int) Setting::get($type.'_day_end'); 
	
		// Set the current date with the specified day
		$currentDate = Carbon::now()->day($day);

		if ($billingStart == 'previous_month') {
			// if billing start is previous then this should be current
			// No need to adjust, already in the current month
		} elseif ($billingStart == 'current_month') {
			// billing start is current then this should be next month
			$currentDate->addMonthNoOverflow(); 
		} else {
			throw new InvalidArgumentException("Setting::get('".$type."_billing_end') is empty or invalid!");
		}
	
		return $currentDate->toDateString();
	}
}


// fiberDateCutOff
// p2pDateCutOff



// end billing


if (! function_exists('currencyFormat')) {
	function currencyFormat($value) {
		return config('app-settings.currency_prefix').' '.
			number_format(
				$value, 
				config('app-settings.decimal_precision')
			);
        
	}
}

// link
if (! function_exists('coordinatesLink')) {
	function coordinatesLink($value) {
		return '
			<a href="'."https://www.google.com/maps?q=".$value.'"
				target="_blank"    
			>
				'.$value.'
			</a>
		';
        
	}
}