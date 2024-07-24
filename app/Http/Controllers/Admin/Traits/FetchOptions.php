<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Otc;
use App\Models\Customer;
use App\Models\BillingType;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\BillingStatus;
use App\Models\ContractPeriod;
use App\Models\PlannedApplication;

trait FetchOptions
{
    public function customerLists()
    {
        $customers = Customer::all();

        return $customers->mapWithKeys(function ($customer) {
            return [$customer->id => $customer->full_name];
        })->toArray();
    }

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

    public function plannedApplicationLists()
    {
        $entries = PlannedApplication::all();

        return $entries->mapWithKeys(function ($entry) {
            return [$entry->id => $entry->details];
        })->toArray();
    }

    public function oneTimeChargeLists()
    {
        $entries = Otc::all();

        return $entries->mapWithKeys(function ($entry) {
            return [$entry->id => $entry->amount_name];
        })->toArray();
    }

    public function contractPeriodLists()
    {
        return ContractPeriod::pluck('name', 'id')->toArray();
    }

}
