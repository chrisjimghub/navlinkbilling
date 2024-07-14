<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
// end carbon


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