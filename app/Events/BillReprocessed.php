<?php

namespace App\Events;

use App\Models\Billing;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BillReprocessed
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Billing $billing
    )
    {
        //
    }
   
}
