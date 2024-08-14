<?php

namespace App\Console\Commands;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Traits\SendNotifications;

class AutoSendCutOffNotification extends Command
{
    use SendNotifications;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:send-cut-off-notification';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically sends a cut-off notification for bills.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $billings = Billing::whereNull('cut_off_notified_at')
                    ->monthly()  
                    ->unpaid()
                    ->get();


        foreach ($billings as $billing) {
            $group = $billing->account->billingGrouping;
            $subDays = $group->bill_cut_off_notification_days_before_cut_off_date;
            $dateRun = Carbon::parse($billing->date_cut_off)->subDays($subDays);

            if (!$dateRun->isToday()) {
                continue;
            }

            $customer = $billing->account->customer;
            if (empty($customer->email) || $customer->email == null) {
                continue;
            }

            $this->cutOffNotification($customer, $billing);
            sleep(1);
        }
    }
}
