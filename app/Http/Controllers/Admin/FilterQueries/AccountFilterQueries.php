<?php  

namespace App\Http\Controllers\Admin\FilterQueries;

trait AccountFilterQueries
{
    public function accountFilterQueries($query)
    {
        $status = request()->input('status');
        $sub = request()->input('subscription');
        $grouping = request()->input('grouping');

        if ($status) {
            $query->withStatus($status);
        }

        if ($sub) {
            $query->withSubscription($sub);
        }

        if ($grouping) {
            $query->withBillingGrouping($grouping);
        }

        return $query;
    }
}
