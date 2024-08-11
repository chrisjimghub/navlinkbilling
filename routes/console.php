<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bill:auto-generate')->everyMinute()->withoutOverlapping();
Schedule::command('bill:auto-send-notification')->everyMinute()->withoutOverlapping();
Schedule::command('bill:auto-send-cut-off-notification')->everyMinute()->withoutOverlapping();