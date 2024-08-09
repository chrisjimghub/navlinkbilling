<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Account;
use InvalidArgumentException;
use App\Models\BillingGrouping;
use App\Events\GenerateBillEvent;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Admin\Traits\BillingPeriod;

trait GenerateBill
{
    use BillingPeriod;

    public function generateBill(BillingGrouping|Collection $grouping)
    {
        $groupings = null;

        if ($grouping instanceof Collection) {
            $groupings = $grouping->all();
        } elseif ($grouping instanceof BillingGrouping) {
            $groupings = [$grouping];
        } else {
            throw new InvalidArgumentException('Invalid type for $grouping. Expected BillingGrouping or Collection.');
        }

        foreach ($groupings as $group) {
            $accounts = Account::allowedBill()->where('billing_grouping_id', $group->id)->get();
            event(new GenerateBillEvent($accounts));
        }
    }
}
