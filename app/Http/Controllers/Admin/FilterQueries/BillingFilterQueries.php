<?php

namespace App\Http\Controllers\Admin\FilterQueries;

use Illuminate\Support\Carbon;

trait BillingFilterQueries
{
    public function billingFilterQueries($query)
    {
        
        $status = request()->input('status');
        if ($status) {
            $query->where('billing_status_id', $status);
            
        }
        
        $type = request()->input('type');
        if ($type) {
            $query->where('billing_type_id', $type);
        }
        
        $period = request()->input('period');
        if ($period) {
            $dates = explode('-', $period);
            $dateStart = Carbon::parse($dates[0]);
            $dateEnd = Carbon::parse($dates[1]);
            $query->withinBillingPeriod($dateStart, $dateEnd);
        }

        $monthYear = request()->input('my');
        if ($monthYear) {
            $monthYear = explode('-', $monthYear);
            
            if (count($monthYear) == 2) {
                $query->whereMonth('date_end', $monthYear[1]);
                $query->whereYear('date_end', $monthYear[0]);
            }
        }

        return $query;
    }
}
