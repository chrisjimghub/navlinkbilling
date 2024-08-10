<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Support\Carbon;
use App\Models\BillingGrouping;
use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Traits\GenerateBill;
use App\Http\Controllers\Admin\Traits\BillingPeriod;

class AutoGenerateBillCommand extends Command
{
    use BillingPeriod;
    use GenerateBill;

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
    protected $description = 'Automatically generate bills for all accounts';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groupings = BillingGrouping::all();

        foreach ($groupings as $group) {
            $this->info('Generating group '.$group->name.'....');
            $period = $this->billingPeriod($group);

            $subDays = $group->bill_generate_days_before_end_of_billing_period;
            $dateRun = Carbon::parse($period['date_end'])->subDays($subDays);

            if ($dateRun->isToday()) {
                $this->generateBill($group);
            }
        }

        $this->info('Bills generated successfully.');
    }
}
