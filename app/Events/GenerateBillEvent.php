<?php

namespace App\Events;

use App\Models\Account;
use InvalidArgumentException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class GenerateBillEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $accounts;
    /**
     * Create a new event instance.
     */
    public function __construct(Account|Collection $accounts)
    {
        if ($accounts instanceof Collection) {
            $this->accounts = $accounts->all();
        } elseif ($accounts instanceof Account) {
            $this->accounts = [$accounts];
        } else {
            throw new InvalidArgumentException('Invalid type for $accounts. Expected Account or Collection.');
        }
    }
    
}
