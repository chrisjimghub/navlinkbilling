<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
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

            $subDays = (int) Setting::get('days_before_generate_bill');

            

            dd([
                'sub_days' => $subDays,
            ]);

            // Artisan::call('bill:generate', ['--fiber' => true]);
            // Artisan::call('bill:generate', ['--p2p' => true]);
            // dd([
            //     $fiberRunOnDate->toDateString(),
            //     $p2pRunOnDate->toDateString()
            // ]);
            
        }

    }
}
