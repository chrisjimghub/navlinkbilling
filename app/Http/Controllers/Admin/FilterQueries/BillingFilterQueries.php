<?php

namespace App\Http\Controllers\Admin\FilterQueries;

use Illuminate\Support\Carbon;

trait BillingFilterQueries
{
    public function billingFilterQueries($query)
    {
        $status = request()->input('status');
        $type = request()->input('type');
        $period = request()->input('period');

        if ($status) {
            $query->where('billing_status_id', $status);
        
        }

        if ($type) {
            $query->{$type == 1 ? 'installment' : 'monthly'}();
        }

        if ($period) {
            $dates = explode('-', $period);
            $dateStart = Carbon::parse($dates[0]);
            $dateEnd = Carbon::parse($dates[1]);
            $query->withinBillingPeriod($dateStart, $dateEnd);
        }

        return $query;
    }
}
