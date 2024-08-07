<?php

namespace App\Exports;

use App\Models\Otc;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use App\Models\BillingGrouping;
use App\Models\PlannedApplication;
use App\Exports\Traits\ExportHelper;
use App\Models\Traits\SchemaTableColumn;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccountUploadTemplateExport implements ShouldAutoSize, WithEvents
{
    use Exportable, ExportHelper, SchemaTableColumn;

    public function headers()
    {
        $headers = $this->getColumns('accounts');

        $headers[] = 'one_time_charge';
        $headers[] = 'contract_period';

        // Modify headers by reference to remove '_id' suffix
        foreach ($headers as &$header) {
            if (str_ends_with($header, '_id')) {
                $header = substr($header, 0, -3); // Remove '_id' from the end
            }
        }
        unset($header); // Break the reference

        return $headers;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                
                $this->setTextBold($sheet, 1);        

                // Write headers to the sheet
                $col = 'A';
                foreach ($this->headers() as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $col++;
                }

                $this->convertColumnFormatIntoText($sheet);
                
                // Create and populate the hidden sheet for customer names
                $customerNames = Customer::all()->pluck('full_name')->toArray();
                $this->createHiddenSheet($sheet, 'Customers', $customerNames);

                // plan app hidden sheet
                $planApp = PlannedApplication::join('locations', 'planned_applications.location_id', '=', 'locations.id')
                    ->join('planned_application_types', 'planned_applications.planned_application_type_id', '=', 'planned_application_types.id')
                    ->orderBy('locations.name', 'asc')
                    ->orderBy('planned_application_types.name', 'asc')
                    ->select('planned_applications.*')
                    ->get()
                    ->pluck('details')
                    ->toArray();
                $this->createHiddenSheet($sheet, 'PlannedApps', $planApp);

                // subscription
                $sub = Subscription::all()->pluck('name')->toArray();
                $this->createHiddenSheet($sheet, 'Subscriptions', $sub);

                // acc status
                $status = AccountStatus::all()->pluck('name')->toArray();
                $this->createHiddenSheet($sheet, 'AccountStatus', $status);

                $otc = Otc::all()->pluck('amount_name')->toArray();
                $this->createHiddenSheet($sheet, 'Otcs', $otc);
            
                $contract = ContractPeriod::all()->pluck('name')->toArray();
                $this->createHiddenSheet($sheet, 'ContractPeriods', $contract);

                $grouping = BillingGrouping::all()->pluck('name')->toArray();
                $this->createHiddenSheet($sheet, 'BillingGroupings', $grouping);
            },

            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                
                $this->listDataValidation($sheet, 'Customers', 'A');                
                $this->listDataValidation($sheet, 'PlannedApps', 'B');                
                $this->listDataValidation($sheet, 'Subscriptions', 'C');                
                $this->listDataValidation($sheet, 'AccountStatus', 'H');                
                $this->listDataValidation($sheet, 'BillingGroupings', 'I');                
                $this->listDataValidation($sheet, 'Otcs', 'J');                
                $this->listDataValidation($sheet, 'ContractPeriods', 'K');                
            },
            
            
        ];
    }


}
