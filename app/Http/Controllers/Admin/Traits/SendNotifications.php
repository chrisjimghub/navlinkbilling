<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Billing;
use App\Models\Customer;
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
    }
}
