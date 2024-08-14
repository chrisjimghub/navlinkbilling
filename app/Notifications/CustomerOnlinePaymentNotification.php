<?php

namespace App\Notifications;

use App\Models\Billing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class CustomerOnlinePaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use CurrencyFormat;
    /**
     * Create a new notification instance.
     */
    public function __construct(public Billing $billing)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'model' => 'Billing',
            'id' => $this->billing->id,
            'type' => 'success',
            'message' => '
                Customer '.$this->billing->account->customer->full_name.' has successfully made a payment. The payment details are as follows:
                <br>• Account: <a class="btn btn-sm btn-primary" href="'.route('billing.show', $this->billing->id).'">'.$this->billing->account->details.'</a>
                <br>• Total Balance: '.$this->currencyFormatAccessor($this->billing->total).'
            ',
        ];
    }
}
