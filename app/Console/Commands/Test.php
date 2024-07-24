<?php

namespace App\Console\Commands;

use App\Models\Otc;
use App\Models\Subscription;
use Illuminate\Console\Command;
use App\Models\PlannedApplication;
use Backpack\Settings\app\Models\Setting;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Http\Controllers\Admin\Traits\PlannedApplicationCrud;

class Test extends Command
{
    use FetchOptions;
    use PlannedApplicationCrud;

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
        // $temp = 'Villaba Proper - Residential : 30Mbps --- ₱2,800.00';
        // $temp = 'Tabunok Sabang - Residential : 20Mbps --- ₱1,200.00';
        // $temp = 'FIBER';
        // $otc = new Otc;
        // $temp = 'Swap Antenna / Router Free Installation';
        // $temp = '₱4,500.00 OTC (P2P)';
        // $temp = '₱2,500.00 OTC (FIBER)';
        // $temp = 'Free Installation';
        // $temp = 'Villaba Proper - Residential : 10Mbps --- ₱1,200.00';
        $temp = 'Advance 1-month monthly payment| With 12 months Lock-in';

        $cts = explode('|', $temp);

        dd($cts);



        dd(
            // PlannedApplication::whereDetails($temp)->first()
            // $this->parseDetails($temp)
            // PlannedApplication::whereDetails('Abijao - For Business : 10Mbps --- ₱1,200.00')->first()->toArray()
            // Subscription::whereLike('name', $temp)->first()->id
            // $otc->parseAmountName($temp)
            // Otc::whereAmountName($temp)->get()->toArray()
        );
    }

}
