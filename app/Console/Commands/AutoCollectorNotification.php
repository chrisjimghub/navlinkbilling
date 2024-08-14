<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Models\PisoWifiCollector;
use App\Http\Controllers\Admin\Traits\SendNotifications;

class AutoCollectorNotification extends Command
{
    use SendNotifications;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:collector-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sechedule notifications to collectors';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        // Query to get records where last_notified_at is either not today or is null
        $collectors = PisoWifiCollector::where(function ($query) use ($today) {
            $query->whereDate('last_notified_at', '!=', $today)
                ->orWhereNull('last_notified_at');
        })->get();

        foreach ($collectors as $collector) {
            $scheduleDay = adjustDayWithinMonth($collector->schedule);
            $scheduleDate = Carbon::now()->setDay($scheduleDay);

             // Check if the schedule date is equal to today
            if ($scheduleDate->isSameDay(Carbon::today())) {
                $this->collectorNotification($collector);
            } 
        }        
    }
}
