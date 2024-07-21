<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\BillingType;
use App\Models\AccountStatus;
use App\Models\BillingStatus;
use App\Models\Subscription;

trait FetchOptions
{
    public function accountStatusLists()
    {
        return AccountStatus::pluck('name', 'id')->toArray();
    }

    public function billingStatusLists()
    {
        return BillingStatus::pluck('name', 'id')->toArray();
    }

    public function billingTypeLists()
    {
        return BillingType::pluck('name', 'id')->toArray();
    }

    public function subscriptionLists()
    {
        return Subscription::pluck('name', 'id')->toArray();
    }

}
