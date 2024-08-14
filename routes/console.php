<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('auto:generate-bill')->everyMinute()->withoutOverlapping();
Schedule::command('auto:send-notification')->everyMinute()->withoutOverlapping();
Schedule::command('auto:send-cut-off-notification')->everyMinute()->withoutOverlapping();