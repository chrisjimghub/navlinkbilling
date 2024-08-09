<?php

namespace App\Imports;

use App\Models\Otc;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use Illuminate\Validation\Rule;
use App\Models\PlannedApplication;
use App\Rules\PipelineSeparatedIn;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Rules\GoogleMapCoordinatesValidator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Http\Controllers\Admin\Traits\FetchOptions;
use App\Models\BillingGrouping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AccountImport implements 
    ToModel, 
    WithValidation, 
    WithHeadingRow,
    WithMultipleSheets
{

    use FetchOptions;

    public function model(array $row)
    {
        $customerId = Customer::whereFullName($row['customer'])->pluck('id')->first();
        $plannedApplicationId = PlannedApplication::whereDetails($row['planned_application'])->pluck('id')->first();
        $subscriptionId = Subscription::whereLike('name', trim($row['subscription']))->pluck('id')->first();

        $otcs = explode('|', $row['one_time_charge']);
        $otcIds = [];
        foreach ($otcs as $otc) {
            $otcIds[] = Otc::whereAmountName(trim($otc))->pluck('id')->first();
        }

        $contracts = explode('|', $row['contract_period']);
        $contractPeriodIds = [];
        foreach ($contracts as $cp) {
            $contractPeriodIds[] = ContractPeriod::whereLike('name', trim($cp))->pluck('id')->first();
        }

        $statusId = AccountStatus::whereLike('name', trim($row['account_status']))->pluck('id')->first();
        $grouping = BillingGrouping::whereLike('name', trim($row['billing_grouping']))->pluck('id')->first();
        
        $account = new Account([
            'customer_id' => $customerId,
            'planned_application_id' => $plannedApplicationId,
            'subscription_id' => $subscriptionId,
            'installed_date' => $row['installed_date'],
            'installed_address' => $row['installed_address'],
            'google_map_coordinates' => $row['google_map_coordinates'],
            'notes' => $row['notes'],
            'account_status_id' => $statusId,
            'billing_grouping_id' => $grouping,
        ]);

        $account->save();

        // attach otc and contract pivot
        $account->otcs()->attach($otcIds);
        $account->contractPeriods()->attach($contractPeriodIds);
    }

    public function rules(): array
    {
        return [
            
            'customer' => [
                'required', 
                Rule::in($this->customerLists()), // Check if the value is in the provided list
            ],

            'planned_application' => [
                'required', 
                Rule::in($this->plannedApplicationLists()), 
            ],

            'subscription' => [
                'required', 
                Rule::in($this->subscriptionLists()), 
            ],

            'one_time_charge' => [
                'required', 
                new PipelineSeparatedIn($this->oneTimeChargeLists()), 
            ],

            // Contract Period
            'contract_period' => [
                'required', 
                new PipelineSeparatedIn($this->contractPeriodLists()), 
            ],

            'installed_date' => 'nullable|date',

            // Installed address

            // Google map coordinates
            'google_map_coordinates' => ['nullable', new GoogleMapCoordinatesValidator],
            
            // Notes

            'account_status' => [
                'required', // Make this field mandatory
                Rule::in($this->accountStatusLists()), // Check if the value is in the provided list
            ],

            'billing_grouping' => [
                'required', // Make this field mandatory
                Rule::in($this->billingGroupingLists()), // Check if the value is in the provided list
            ],


        ];
    }

    public function sheets(): array
    {
        return [
            0 => $this, // Use the same class for the first sheet
        ];
    }
}
