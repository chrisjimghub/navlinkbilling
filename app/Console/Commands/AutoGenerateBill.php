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
        if (Setting::get('auto_generate_bill') && Setting::get('auto_generate_bill') == "1") {

            // Fiber
            $fiberRunOnDate = Setting::get('fiber_date_end') ? dateOfMonth(Setting::get('fiber_date_end'), true) : now()->endOfMonth();
            if (Setting::get('days_generate_bill')) {
                $fiberRunOnDate = $fiberRunOnDate->subDays((int) Setting::get('days_generate_bill'));
            }

            if (Carbon::now()->isSameDay($fiberRunOnDate)) {
                Artisan::call('bill:generate', ['--fiber' => true]);
            }


            // P2P
            $p2pRunOnDate = Setting::get('p2p_date_end') ? dateOfMonth(Setting::get('p2p_date_end'), true) : dateOfMonth(20, true);
            if (Setting::get('days_generate_bill')) {
                $p2pRunOnDate = $p2pRunOnDate->subDays((int) Setting::get('days_generate_bill'));
            }

            if (Carbon::now()->isSameDay($p2pRunOnDate)) {
                Artisan::call('bill:generate', ['--p2p' => true]);
            }


            // dd([
            //     $fiberRunOnDate->toDateString(),
            //     $p2pRunOnDate->toDateString()
            // ]);
            
        }

    }
}
