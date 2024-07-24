<?php

namespace App\Exports;

use App\Models\Otc;
use App\Models\Subscription;
use App\Models\AccountStatus;
use App\Models\ContractPeriod;
use App\Models\PlannedApplication;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccountOptionsColumnExport implements 
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;

    
    protected function entries()
    {
        $entries = [];

        $entries['plan'] = PlannedApplication::join('locations', 'planned_applications.location_id', '=', 'locations.id')
            ->join('planned_application_types', 'planned_applications.planned_application_type_id', '=', 'planned_application_types.id')
            ->orderBy('locations.name', 'asc')
            ->orderBy('planned_application_types.name', 'asc')
            ->select('planned_applications.*')
            ->get();

        $entries['status'] = AccountStatus::orderBy('name', 'asc')->get();
        
        $entries['otc'] = Otc::all();

        $entries['contract'] = ContractPeriod::all();

        $entries['sub'] = Subscription::all();

        return $entries;
    }

    
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object

                $this->setTextBold($sheet, 1);

                // Define headers in row 5
                $headers = [
                    'planned_application', 
                    'account_status', 
                    'one_time_charge',
                    'contract_period',
                    'subscription', 
                ];

                // Write headers to the sheet
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $col++;
                }
            },

            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                
                // plann app
                $col = 'A';
                $row = 2; 
                foreach ($this->entries()['plan'] as $entry) {
                    $sheet->setCellValue($col . $row++, $entry->details);
                }

                // status
                $col = 'B';
                $row = 2; 
                foreach ($this->entries()['status'] as $entry) {
                    $sheet->setCellValue($col . $row++, $entry->name);
                }
            
                // otc 
                $col = 'C';
                $row = 2; 
                foreach ($this->entries()['otc'] as $entry) {
                    $sheet->setCellValue($col . $row++, $entry->amount_name);
                }

                // contract  
                $col = 'D';
                $row = 2; 
                foreach ($this->entries()['contract'] as $entry) {
                    $sheet->setCellValue($col . $row++, $entry->name);
                }

                // subscription  
                $col = 'E';
                $row = 2; 
                foreach ($this->entries()['sub'] as $entry) {
                    $sheet->setCellValue($col . $row++, $entry->name);
                }

            },
        ];
    }

    
}
