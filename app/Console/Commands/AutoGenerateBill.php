<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Backpack\Settings\app\Models\Setting;

class AutoGenerateBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:auto-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        if (Setting::get('enable_auto_bill') && Setting::get('enable_auto_bill') == "1") {

            
        }

    }

    private function dateRunIsToday($period)
    {
        $subDays = (int) Setting::get('days_before_generate_bill');
        $dateRun = Carbon::parse($period['date_end'])->subDays($subDays);

        if ($dateRun->isToday()) {
            return true;
        }

        return false;
    }
}
