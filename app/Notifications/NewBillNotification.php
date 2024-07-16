<?php

namespace App\Notifications;

use App\Models\Billing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBillNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $billing;

    /**
     * Create a new notification instance.
     */

    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // return (new MailMessage)
        //     ->subject(
        //         $this->billing->isCutOff() ? 
        //             'Cut Off Notification For '.$this->billing->month.'.' : 
        //             'Bill For The Month Of '.$this->billing->month.'.') 
        //     ->markdown(
        //         $this->billing->isCutOff() ? 'emails.cut_off' : 'emails.new-bill', 
        //         ['billing' => $this->billing]
        //     );

        return (new MailMessage)
            ->subject('Bill For The Month Of '.$this->billing->month) 
            ->markdown('emails.new-bill', ['billing' => $this->billing]);
    }

}
