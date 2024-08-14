<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\User;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\PisoWifiCollector;
use App\Notifications\CollectorNotification;
use App\Notifications\CustomerOnlinePaymentNotification;
use App\Notifications\CutOffNotification;
use App\Notifications\NewBillNotification;
use Illuminate\Support\Facades\Notification;

trait SendNotifications
{
    public function billNotification(Customer $customer, Billing $billing, $queue = 'default')
    {
        $customer->notify((new NewBillNotification($billing))->onQueue($queue));
        $billing->notified_at = now();
        $billing->saveQuietly();
    }

    public function cutOffNotification(Customer $customer, Billing $billing, $queue = 'default')
    {
        $customer->notify((new CutOffNotification($billing))->onQueue($queue));
        $billing->cut_off_notified_at = now();
        $billing->saveQuietly();


        // Get the users with the specific permission
        $users = User::permission('notifications_cut_off')->get();
        // Create a notification instance and specify the queue
        $notification = (new CutOffNotification(billing: $billing, via: 'database'))->onQueue($queue);
        // Send the notification in bulk
        Notification::send($users, $notification);
    }

    public function customerOnlinePaymentNotification(Billing $billing, $queue = 'default')
    {
        if (!$billing->isPaid()) {
            return;
        }

        // Get the users with the specific permission
        $users = User::permission('notifications_customer_payment')->get();
        // Create a notification instance and specify the queue
        $notification = (new CustomerOnlinePaymentNotification($billing))->onQueue($queue);
        // Send the notification in bulk
        Notification::send($users, $notification);
    }

    public function collectorNotification(PisoWifiCollector $pisoWifi, $queue = 'default')
    {
        
        $users = $pisoWifi->users;

        foreach ($users as $user) {
            $user->notify((new CollectorNotification($pisoWifi, $user))->onQueue($queue));
        }

        $pisoWifi->last_notified_at = now();
        $pisoWifi->saveQuietly();
    }
}
