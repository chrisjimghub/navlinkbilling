<?php

namespace App\Exports;

use App\Models\Customer;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CustomerExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;

    protected $title = 'Customers';

    protected function entries()
    {
        return Customer::all();
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

                $sheet->setCellValue('B1', $this->title);
                $sheet->setCellValue('B2', 'Generated: '. carbonNow());
                
                $this->setTextBold($sheet, 5);

                /* 
                        'customer'                   => 'Customer',
                        'customer_name'              => 'Customer Name',
                        'customer_signature'         => 'Please sign here',
                        'customer_date_birth'        => 'Date of Birth',
                        'customer_contact'           => 'Contact Number',
                        'customer_street'            => 'Block Street',
                        'customer_barangay'          => 'Barangay',
                        'customer_city_municipality' => 'City or Municipality',
                        'customer_social'            => 'Social Media',
                */

                // Define headers in row 5
                $headers = [
                    __('app.row_num'), 
                    __('app.customer_name'), 
                    __('app.customer_date_birth'), 
                    __('app.customer_contact'), 
                    __('app.email'),
                    __('app.customer_street'),
                    __('app.customer_barangay'),
                    __('app.customer_city_municipality'),
                    __('app.customer_social'),
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
                    $sheet->setCellValue($col++ . $row, $entry->full_name); 
                    $sheet->setCellValue($col++ . $row, $entry->date_of_birth); 
                    $sheet->setCellValue($col++ . $row, $entry->contact_number); 
                    $sheet->setCellValue($col++ . $row, $entry->email); 
                    $sheet->setCellValue($col++ . $row, $entry->block_street); 
                    $sheet->setCellValue($col++ . $row, $entry->barangay); 
                    $sheet->setCellValue($col++ . $row, $entry->city_or_municipality); 
                    $sheet->setCellValue($col++ . $row, $entry->social_media); 

                    $row++;
                }
            },
        ];
    }

    
}
