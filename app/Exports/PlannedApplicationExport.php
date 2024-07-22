<?php

namespace App\Exports;

use App\Models\PlannedApplication;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class PlannedApplicationExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;

    protected $title = 'Planned Applications';

    protected function entries()
    {
        return PlannedApplication::join('locations', 'planned_applications.location_id', '=', 'locations.id')
            ->join('planned_application_types', 'planned_applications.planned_application_type_id', '=', 'planned_application_types.id')
            ->orderBy('locations.name', 'asc')
            ->orderBy('planned_application_types.name', 'asc')
            ->select('planned_applications.*')
            ->get();

    }


    public function startCell(): string
    {
        return 'A5'; // Start data from cell A5
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object

                $this->excelTitle($sheet);
                
                $this->setTextBold($sheet, 5);


                // Define headers in row 5
                $headers = [
                    __('app.row_num'), 
                    __('app.location'), 
                    __('app.planned_application_type'), 
                    __('app.planned_application_mbps'), 
                    __('app.planned_application_price'),
                    __('app.planned_application_select'),
                ];

                // Write headers to the sheet
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '5', $header);
                    $col++;
                }

                 // Freeze the first two columns (A and B)
                 $sheet->freezePane('C6');
            },

            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $row = 6; // Start from row 6 for data
                $col = 'A'; // Starting column

                $num = 1;
                foreach ($this->entries() as $entry) {
                    $col = 'A'; // Reset column for each row

                    $sheet->setCellValue($col++ . $row, $num++); 
                    $sheet->setCellValue($col++ . $row, $entry->location->name); 
                    $sheet->setCellValue($col++ . $row, $entry->plannedApplicationType->name); 
                    $sheet->setCellValue($col++ . $row, $entry->mbps); 

                    $this->setCellNumberFormat($sheet, $col . $row);
                    $sheet->setCellValue($col++ . $row, $entry->price); 

                    $sheet->setCellValue($col++ . $row, $entry->option_label);

                    $row++;
                }
            },
        ];
    }

    
}
