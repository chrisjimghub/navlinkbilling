<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Traits\SendNotifications;
use App\Models\Billing;
use App\Notifications\CutOffNotification;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Backpack\Settings\app\Models\Setting;

class AutoSendNotification extends Command
{
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
    protected $description = 'Automatically send bill notifications to customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Setting::get('enable_auto_bill') && Setting::get('enable_auto_bill') == "1") {

            $this->sendBillNotifications();
            $this->sendCutOffNOtifications();
        }

    }
    
    private function sendCutOffNOtifications()
    {
        $subDays = (int) Setting::get('days_before_send_cut_off_notification');

        $billings = Billing::whereNull('cut_off_notified_at')
                        ->unpaid()
                        ->get();

        foreach ($billings as $bill) {
            $customer = $bill->account->customer;

            // Check if customer email is not empty
            if (empty($customer->email)) {
                // Log the issue
                Log::warning('Customer email is empty for billing ID: ' . $bill->id);
                continue;
            }

            $dateRun = Carbon::parse($bill->date_cut_off)->subDays($subDays);

            if (!$dateRun->isToday()) {
                // dump('NOT TODAY id:' . $bill->id. ' - '.$dateRun->toDateString());
                continue;

            }

            // dump('TODAY id:' . $bill->id. ' - '.$dateRun->toDateString());

            $this->cutOffNotification($customer, $bill);

            sleep(1);

        }// end foreach

        // dd();
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

            $this->billNotification($customer, $bill);

            sleep(1);

        }// end foreach

        // dd();
    }

}
