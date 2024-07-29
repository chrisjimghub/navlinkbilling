<?php

namespace App\Notifications;

use App\Models\Billing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class CutOffNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use CurrencyFormat;

    protected $billing;
    protected $via;

    /**
     * Create a new notification instance.
     */
    public function __construct(Billing $billing, $via = null)
    {
        //
        $this->billing = $billing;
        $this->via = $via;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Check if $this->via is set
        if ($this->via) {
            // If $this->via is set, ensure it is returned as an array
            if (is_array($this->via)) {
                return $this->via;
            } else {
                return [$this->via];
            }
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cut Off Notification For '.$this->billing->month) 
            ->markdown('emails.cut-off', ['billing' => $this->billing]);
    }

    // toDatbase / toArray
    public function toArray($notifiable)
    {
        return [
            //
            'model' => 'Billing',
            'id' => $this->billing->id,
            'message' => '
                Please be advised that Mr./Mrs. '.$this->billing->account->customer->full_name.
                ' has an outstanding balance of '.$this->currencyFormatAccessor($this->billing->total).
                ' for the month of '.$this->billing->month.' '.$this->billing->year.
                '. As the account remains unpaid, please proceed with the necessary actions to cut off the connection.
                <br>
                Account: <a href="'.route('account.show', $this->billing->account->id).'">'.$this->billing->account->details.'</a>.
            ',
        ];
    }

}
