<?php

namespace App\Events;

use App\Models\Account;
use App\Models\Billing;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BillProcessed
{
    use Dispatchable, SerializesModels;

    public $billing;

    /**
     * Create a new event instance.
     */
    public function __construct(Billing|Account $model)
    {
        if ($model instanceof Billing) {
            $this->billing = $model;

        } elseif ($model instanceof Account) {
            $this->billing = $model->billings()
                                ->unpaid()
                                ->get();
        }

        // debug('Bill Processed event is fired');
    }
    
}
