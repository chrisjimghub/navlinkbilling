<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

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

if (! function_exists('containsDayPatternAndProRated')) {
	/**
     * Check if the string(particulars) contains the pattern (n day) or (n days) and optionally "Pro-rated".
     *
     * @param string $string The string to check.
     * @return bool True if the pattern is found, false otherwise.
     */
    function containsDayPatternAndProRated($string)
    {
        return preg_match('/(Pro-rated\s*)?\(\d+\s*day(s?)\)|\(\s*Pro-rated\s*\d+\s*day(s?)\)/i', $string) === 1; 
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
if (! function_exists('carbonInstance')) {
	function carbonInstance($date) {
		return Carbon::parse($date);
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

// 
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

if (! function_exists('alertInfo')) {
	function alertInfo($msg) {
		\Alert::info('<strong>'.__('Info!').'</strong><br>'.$msg)->flash();
	}
}

if (! function_exists('alertError')) {
	function alertError($msg) {
		\Alert::error('<strong>'.__('Error!').'</strong><br>'.$msg)->flash();
	}
}

if (! function_exists('alertSuccess')) {
	function alertSuccess($msg) {
		\Alert::success('<strong>'.__('Success!').'</strong><br>'.$msg)->flash();
	}
}

// 
if (! function_exists('isBootstrap4')) {
	function isBootstrap4() {
		if (config('backpack.ui.view_namespace') == 'backpack.theme-coreuiv2::') {
			return true;
		}
		
		return false;
	}
}

if (! function_exists('booleanYesOrNo')) {
    function booleanYesOrNo($value) {
        $boolean = (bool) $value;
        return $boolean ? 'Yes' : 'No';
    }
}

if (! function_exists('badgeSuccess')) {
    function badgeSuccess($value) {
		return "<span class='badge badge-success'>{$value}</span>";
    }
}

if (! function_exists('badgeDanger')) {
    function badgeDanger($value) {
		return "<span class='badge badge-danger'>{$value}</span>";
    }
}

// Number
if (! function_exists('ordinal')) {
    function ordinal($num) {
		return Number::ordinal($num);
	}
}

// widgets
if (! function_exists('widgetProgress')) {
    function widgetProgress($score, $maxScore) {
		if ($maxScore == 0) {
			return 0;
		}

		return ($score / $maxScore) * 100; 
	}
}

//month
if (! function_exists('monthText')) {
    function monthText($numericMonth) {
		return Carbon::createFromFormat('m', $numericMonth)->format('F');
	}
}

