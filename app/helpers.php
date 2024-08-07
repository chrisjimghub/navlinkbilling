<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Backpack\Settings\app\Models\Setting;

// Str
if (! function_exists('strHumanReadable')) {
	function strHumanReadable($string) {
		// Convert camel case to snake case with underscores
		$snake = Str::snake($string);

		// Replace underscores with spaces
		$spaced = str_replace('_', ' ', $snake);

		// Convert to title case
		$humanReadable = Str::title($spaced);

		return $humanReadable;
	}
}

if (! function_exists('stringStartsWith')) {
	function stringStartsWith($haystack, $needle) {
		return Str::startsWith($haystack, $needle);
	}
}
// end Str



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

// DATES / related to dates
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
	function adjustDayWithinMonth($day, $currentDate = null) {
		
		if ($currentDate) {
			$currentDate = Carbon::parse($currentDate);
		}else {
			$currentDate = Carbon::now();
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
if (! function_exists('billingPeriod')) {
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
		
	}

}


if (! function_exists('fiberBillingPeriod')) {
	function fiberBillingPeriod($currentDate = null) {
		$periodInText = Setting::get('fiber_billing_period');
		$dayStart = (int) Setting::get('fiber_day_start'); 
		$dayEnd = (int) Setting::get('fiber_day_end'); 
		$dayCutOff = (int) Setting::get('fiber_day_cut_off'); 

		return billingPeriod(
			billingPeriod: $periodInText, 
			dayStart: $dayStart, 
			dayEnd: $dayEnd, 
			dayCutOff: $dayCutOff,
			billingType: 'fiber',
			currentDate: $currentDate,
		);
	}

}

if (! function_exists('p2pBillingPeriod')) {
	function p2pBillingPeriod($currentDate = null) {
		$periodInText = Setting::get('p2p_billing_period');
		$dayStart = (int) Setting::get('p2p_day_start'); 
		$dayEnd = (int) Setting::get('p2p_day_end'); 
		$dayCutOff = (int) Setting::get('p2p_day_cut_off'); 

		return billingPeriod(
			billingPeriod: $periodInText, 
			dayStart: $dayStart, 
			dayEnd: $dayEnd, 
			dayCutOff: $dayCutOff,
			billingType: 'p2p',
			currentDate: $currentDate,
		);
		
	}

}
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

// Flash / Messages
if (! function_exists('notySuccess')) {
	function notySuccess($msg) {
		return [
			'type' => 'success',
			'msg' => '<strong>'.__('Success!').'</strong><br>'.$msg,
		];
	}
}

if (! function_exists('notyError')) {
	function notyError($msg) {
		return [
			'type' => 'danger',
			'msg' => '<strong>'.__('Error!').'</strong><br>'.$msg,
		];
	}
}

if (! function_exists('notyValidatorError')) {
	function notyValidatorError($validator) {
		return response()->json([
			'errors' => $validator->errors()->all()
		], 422); // HTTP status code for Unprocessable Entity
	}
}

// alias
if (! function_exists('notyValidatorErrors')) {
	function notyValidatorErrors($validator) {
		return notyValidatorError($validator);
	}
}

if (! function_exists('alertValidatorError')) {
	function alertValidatorError($validator) {
		\Alert::error($validator->errors()->all())->flash();
	}
}

// alias
if (! function_exists('alertValidatorErrors')) {
	function alertValidatorErrors($validator) {
		alertValidatorError($validator);
	}
}