<?php

namespace App\Exports;

use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UploadTemplateExport implements 
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;

    public $headers;
    public $entries;

    public function __construct($headers, $entries)
    {
        $this->headers = $headers;
        $this->entries = $entries;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                
                $this->setTextBold($sheet, 1);        

                // Write headers to the sheet
                $col = 'A';
                foreach ($this->headers as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $col++;
                }

            },

            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $row = 2; // Start from row 6 for data
                $col = 'A'; // Starting column

                foreach ($this->entries as $entry) {
                    $col = 'A'; // Reset column for each row

                    $sheet->setCellValue($col++ . $row, $entry->full_name); 

                    $row++;
                }
            },
        ];
    }

    
}
