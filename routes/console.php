<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


Schedule::command('bill:auto-generate')->everyMinute()->withoutOverlapping();
Schedule::command('bill:auto-send-notification')->everyMinute()->withoutOverlapping();