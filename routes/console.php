<?php

use Illuminate\Support\Facades\Schedule;

if (config('app.env') != 'production') {
    Schedule::command('auto:generate-bill')->everyMinute()->withoutOverlapping();
    Schedule::command('auto:send-notification')->everyMinute()->withoutOverlapping();
    Schedule::command('auto:send-cut-off-notification')->everyMinute()->withoutOverlapping();
    Schedule::command('auto:collector-notification')->everyMinute()->withoutOverlapping();
} else {
    Schedule::command('auto:generate-bill')->everyThreeHours()->withoutOverlapping();
    Schedule::command('auto:send-notification')->everyThreeHours()->withoutOverlapping();
    Schedule::command('auto:send-cut-off-notification')->everyThreeHours()->withoutOverlapping();
    Schedule::command('auto:collector-notification')->everyThreeHours()->withoutOverlapping();  // TODO::
}

Schedule::call(function (Schedule $schedule) {
    $schedule->command('backup:clean')->daily()->at('23:30'); // 11:30 PM
    $schedule->command('backup:run')->daily()->at('00:00');   // 12 Midnight
})->daily();

