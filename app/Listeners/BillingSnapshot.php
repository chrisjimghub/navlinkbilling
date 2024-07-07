<?php

namespace App\Listeners;

use App\Events\BillProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BillingSnapshot
{
    protected $billing;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BillProcessed $event): void
    {
        // info('BillingSnapshot: '.$event->billing->id);

        $this->billing = $event->billing;

        $snapshot = [];

        $snapshot['account'] = $this->billing->account->toArray();
        $snapshot['plannedApplication'] = $this->billing->account->plannedApplication->toArray();
        $snapshot['plannedApplicationType'] = $this->billing->account->plannedApplication->plannedApplicationType->toArray();
        $snapshot['location'] = $this->billing->account->plannedApplication->location->toArray();
        $snapshot['subscription'] = $this->billing->account->subscription->toArray();
        $snapshot['otcs'] = $this->billing->account->otcs->toArray();
        $snapshot['contractPeriods'] = $this->billing->account->contractPeriods->toArray();
        $snapshot['accountStatus'] = $this->billing->account->accountStatus->toArray();

        // TODO:: capture the account credits exact amount, for documentation and audit trails and review. each bill  
        // TODO:: make sure when we have a button pay using credits, it will add a -amount row in account credits first, before updating the accountCredits here in snapshot
        // $snapshot['accountCredits'] = $this->account->accountCredits->toArray();
        

        $this->billing->account_snapshot = $snapshot;

        $this->billing->save();
    }
}
