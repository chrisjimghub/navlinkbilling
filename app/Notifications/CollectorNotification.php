<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\PisoWifiCollector;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CollectorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pisoWifi;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(PisoWifiCollector $pisoWifi, User $user)
    {
        $this->pisoWifi = $pisoWifi;
        $this->user = $user;
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
    public function toArray($notifiable)
    {
        return [
            //
            'model' => 'PisoWifiCollector',
            'id' => $this->pisoWifi->id,
            'type' => 'info',
            'message' => '
                Hello '.ucwords($this->user->name).',
                <br>
                This is a reminder of your harvest schedule for today. Please attend to this account as part of your tasks. 
                You’ll receive separate notifications for any other schedules or accounts.
                <br>
                Thank you for your attention!
                <br class="mt-5">
                Schedule Harvest: <a class="btn btn-sm btn-success" href="'.route('account.show', $this->pisoWifi->account->id).'">'.$this->pisoWifi->account->details.'</a>
            ',
        ];
    }
}
