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

    public $account;

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
    }
    
}
