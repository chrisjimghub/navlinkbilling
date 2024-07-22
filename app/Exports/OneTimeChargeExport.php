<?php

namespace App\Exports;

use App\Models\Otc;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OneTimeChargeExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;

    protected $title = 'One-Time Charge';

    protected function entries()
    {
        return Otc::all();
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
                    __('app.otc_name'), 
                    __('app.otc_amount'), 
                    __('app.otc'), 
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
                    $sheet->setCellValue($col++ . $row, $entry->name); 
                    $sheet->setCellValue($col++ . $row, $entry->amount); 
                    $sheet->setCellValue($col++ . $row, $entry->amount_name); 


                    $row++;
                }
            },
        ];
    }

    
}
