<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BillGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public ?Account $account;

    /**
     * Create a new event instance.
     *
     * @param  Account|null  $account
     * @return void
     */
    public function __construct(?Account $account = null)
    {
        $this->account = $account;
    }

    
}
