<?php

namespace App\Console\Commands;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Backpack\Settings\app\Models\Setting;
use App\Notifications\NewBillNotification;

class AutoSendNotification extends Command
{
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
    protected $description = 'Automatically send bill notifications to customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Setting::get('enable_auto_bill') && Setting::get('enable_auto_bill') == "1") {

            $this->sendBillNotifications();
            
            // TODO:: cut off notification

            // dd([
            //     'add_days' => $addDays,
            //     'billings' => $billings->toArray(),
            // ]);
        }

    }
    

    private function sendBillNotifications()
    {
        $billings = Billing::whereNull('notified_at')
                                ->unpaid()
                                ->get();

        $addDays = (int) Setting::get('days_before_send_bill_notification');

                            
        foreach ($billings as $bill) {
            $customer = $bill->account->customer;

            // Check if customer email is not empty
            if (empty($customer->email)) {
                // Log the issue
                Log::warning('Customer email is empty for billing ID: ' . $bill->id);
                continue;
            }

            $dateRun = Carbon::parse($bill->created_at)->addDays($addDays);

            if (!$dateRun->isToday()) {
                // dump('NOT TODAY id:' . $bill->id. ' - '.$dateRun->toDateString());
                continue;

            }

            // dump('TODAY id:' . $bill->id. ' - '.$dateRun->toDateString());

            // Send the notification (assuming you have a notification system set up)
            $customer->notify(
                new NewBillNotification($bill)
            );

            $bill->notified_at = now();
            $bill->saveQuietly();

            sleep(1);

        }// end foreach

        // dd();
    }

}
