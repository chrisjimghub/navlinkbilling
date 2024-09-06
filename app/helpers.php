<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

// Str
if (!function_exists('extractMonthAndConvertToDate')) {
	/**
	 * Extract month name from text and convert to the first day of the month in the specified year.
	 *
	 * @param string $text The text containing the month name.
	 * @param int $year The year for the date.
	 * @return string|null The formatted date or null if no month is found.
	 */
    function extractMonthAndConvertToDate($text, $year = null, $format = 'Y-m-d')
    {
		if (!$year) {
			$year = date('Y');
		}

        // Define an array of month names
        $monthNames = getMonthNames();

        // Use regex to find the month name in the text
        foreach ($monthNames as $monthName) {
            if (stripos($text, $monthName) !== false) {
                // Get the numeric representation of the month
                $monthNumber = getMonthNumber($monthName);
                if ($monthNumber) {
                    // Create a Carbon instance for the 1st day of the found month in the specified year
                    $date = Carbon::create($year, $monthNumber, 1);
                    return $date->format($format); 
                }
            }
        }

        // Return null or handle the case where no month is found
        return null;
    }
}

if (!function_exists('getMonthNames')) {
    function getMonthNames()
    {
        return [
			'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
		];
    }
}

if (!function_exists('getMonthNumber')) {
	/**
	 * Get the numeric representation of a month based on its name.
	 *
	 * @param string $monthName The name of the month.
	 * @return string|null The numeric representation of the month or null if not found.
	 */
    function getMonthNumber($monthName)
    {
        $months = [
            'January' => '01',
            'February' => '02',
            'March' => '03',
            'April' => '04',
            'May' => '05',
            'June' => '06',
            'July' => '07',
            'August' => '08',
            'September' => '09',
            'October' => '10',
            'November' => '11',
            'December' => '12'
        ];

        return $months[ucfirst($monthName)] ?? null;
    }
}

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

if (!function_exists('containsAdvancePayment')) {
    /**
     * Check if the description contains any variation of "Advance Payment".
     *
     * @param string $description
     * @return bool
     */
    function containsAdvancePayment(string $description): bool
    {
        $variations = ['advance payment', 'advanced payment', 'advance pay', 'advance payments'];

        return Str::contains(strtolower($description), $variations);
    }
}

if (!function_exists('validParticularsAdvancePayment')) {
    /**
     * Validates if the description contains "Advance Payment" with exactly one month mentioned.
     * Returns true only if "Advance Payment" is present with one month. Otherwise, returns false.
     *
     * @param string $description
     * @return bool
     */
    function validParticularsAdvancePayment(string $description): bool
    {
        // Ensure that "Advance Payment" is present in the description
        if (containsAdvancePayment($description)) {
            // Find all occurrences of months
            preg_match_all('/\b(january|february|march|april|may|june|july|august|september|october|november|december)\b/i', $description, $matches);

            // Return true if exactly one month is found
            return count($matches[0]) === 1;
        }

        return true; // Return true if "Advance Payment" is not in the description (no need to invalidate it)
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
if (! function_exists('randomDate')) {
	function randomDate() {
		// Get the start and end dates of the current year
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        // Generate a random date within the current year
        return Carbon::createFromTimestamp(mt_rand($startOfYear->timestamp, $endOfYear->timestamp));
	}
}


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

