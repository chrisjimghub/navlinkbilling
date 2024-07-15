<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Backpack\Settings\app\Models\Setting;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

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
        $year = '2024';        

        for ($i = 1; $i <= 12; $i++) {
            $formatted = str_pad($i, 2, '0', STR_PAD_LEFT);
            $date = $year.'-'.$formatted.'-01';

            dump(
                // fiberBillingPeriod($date)
                p2pBillingPeriod($date)
            );
        }


        dd();
    }
}
