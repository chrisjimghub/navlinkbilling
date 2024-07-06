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

// carbon
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
        return 'j M Y';
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