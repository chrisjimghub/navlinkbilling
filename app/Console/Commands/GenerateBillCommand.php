<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Traits\BillingPeriod;
use App\Models\BillingGrouping;

class GenerateBillCommand extends Command
{
    use BillingPeriod;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate bills by groupings';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $accounts = Account::allowedBill()->get();

        // dd($accounts->toArray());

        $groupings = BillingGrouping::all();

        foreach ($groupings as $g) {
            dump(
                $this->billingPeriod($g, '2024-07-01')
            );
        }

        dd(
            'end'
        );
    }
}
