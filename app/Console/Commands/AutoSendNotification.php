<?php

namespace App\Console\Commands;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Traits\BillingPeriod;
use App\Http\Controllers\Admin\Traits\SendNotifications;

class AutoSendNotification extends Command
{
    use BillingPeriod;
    use SendNotifications;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:auto-send-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send billing notifications to all relevant accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $billings = Billing::whereNull('notified_at')
                    ->monthly()  
                    ->unpaid()
                    ->get();

        foreach ($billings as $billing) {
            $group = $billing->account->billingGrouping;
            $addDays = $group->bill_notification_days_after_the_bill_created;
            $dateRun = Carbon::parse($billing->created_at)->addDays($addDays);

            if (!$dateRun->isToday()) {
                continue;
            }

            $customer = $billing->account->customer;
            if (empty($customer->email) || $customer->email == null) {
                continue;
            }

            $this->billNotification($customer, $billing);
            sleep(1);
        }

    }
}
