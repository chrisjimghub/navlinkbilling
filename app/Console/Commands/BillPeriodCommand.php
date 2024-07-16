<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BillPeriodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:period {--year=} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show billing periods for a specific year';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? date('Y');
        $type = $this->option('type') ?? 'fiber'; // fiber or p2p

        for ($i = 1; $i <= 12; $i++) {
            $formatted = str_pad($i, 2, '0', STR_PAD_LEFT);
            $date = $year.'-'.$formatted.'-01';

            if ($type == 'fiber') {
                dump(
                    fiberBillingPeriod($date)
                );
            }elseif ($type == 'p2p') {
                dump(
                    p2pBillingPeriod($date)
                );
            }
            
        }
        
        dd();
        
    }
}
