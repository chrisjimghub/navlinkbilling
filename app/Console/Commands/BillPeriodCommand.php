<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Traits\BillingPeriod;
use App\Models\BillingGrouping;
use Illuminate\Console\Command;

class BillPeriodCommand extends Command
{
    use BillingPeriod;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:period {--year=} {--group=}';

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
        $group = $this->option('group') ?? 1;
        $group = BillingGrouping::findOrFail($group);

        for ($i = 1; $i <= 12; $i++) {
            $formatted = str_pad($i, 2, '0', STR_PAD_LEFT);
            $date = $year.'-'.$formatted.'-01';

            dump($this->billingPeriod($group, $date));
        }
        
        dd();
    }
}
