<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Otc;
use App\Models\Customer;
use App\Models\BillingType;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\BillingGrouping;
use App\Models\BillingStatus;
use App\Models\CommunityString;
use App\Models\ContractPeriod;
use App\Models\PlannedApplication;

trait FetchOptions
{
    public function monthLists()
    {
        return [
            "01" => "January",
            "02" => "February",
            "03" => "March",
            "04" => "April",
            "05" => "May",
            "06" => "June",
            "07" => "July",
            "08" => "August",
            "09" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December",
        ];
    }

    public function yearLists($endYear = 2010)
    {
        $yearOptions = [];

        for ($startYear = date('Y'); $startYear >= $endYear; $startYear--) {
            $yearOptions[$startYear] = $startYear;
        }

        return $yearOptions;
    }

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

    public function billingStatusLists($whereNotId = null)
    {
        if ($whereNotId)  {
            return BillingStatus::whereNotIn('id', (array) $whereNotId)->pluck('name', 'id')->toArray();
        }

        return BillingStatus::pluck('name', 'id')->toArray();
    }

    public function billingTypeLists($whereNotId = null)
    {
        if ($whereNotId)  {
            return BillingType::whereNotIn('id', (array) $whereNotId)->pluck('name', 'id')->toArray();
        }

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

    public function billingGroupingLists()
    {
        return BillingGrouping::pluck('name', 'id')->toArray();
    }
}
