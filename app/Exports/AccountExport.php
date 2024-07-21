<?php

namespace App\Exports;

use App\Models\Account;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use App\Http\Controllers\Admin\Traits\UrlQueryString;

class AccountExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;
    use UrlQueryString;

    protected $title = 'Accounts';

    protected function entries()
    {
        $entries = Account::query();

        // This request input is already validated in the Export operation
        $status = request()->input('status');
        $sub = request()->input('subscription');

        if ($status) {
            $entries->withStatus($status);
        }

        if ($sub) {
            $entries->withSubscription($sub);
        }

        return $entries->get();
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


                // Define headers in row 5
                $headers = [
                    '#', 
                    'Acount Name (Customer)', 
                    'Planned Application', 
                    'Subscription', 
                    'Status', 
                    'Coordinates',
                    'Installed Date',
                    'Installed address',
                    'One-Time Charge',
                    'Contract Period',
                    'Notes',
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
                    $sheet->setCellValue($col++ . $row, $entry->customer->full_name); 
                    $sheet->setCellValue($col++ . $row, $entry->plannedApplication->details); 
                    $sheet->setCellValue($col++ . $row, $entry->subscription->name); 
                    $sheet->setCellValue($col++ . $row, $entry->accountStatus->name); 


                    $coordinates = $entry->google_map_coordinates;
                    if ($coordinates) {
                        // Create a Hyperlink object and set it to the cell
                        $sheet->setCellValue($col . $row, $coordinates);
                        $hyperlink = new Hyperlink($this->googleMapLink($coordinates), 'View Location');
                        $sheet->getCell($col . $row)->setHyperlink($hyperlink);

                        // Apply styling to make the link blue and underlined
                        $this->setTextLinkFormat($sheet, $col . $row);

                        $col++;
                    }else {
                        $sheet->setCellValue($col++ . $row, null);
                    }

                    $sheet->setCellValue($col++ . $row, $entry->installed_address); 
                    $sheet->setCellValue($col++ . $row, $entry->installed_date); 

                    $beforeRow = $row;
                    // otcs
                    foreach ($entry->otcs as $otc) {
                        $sheet->setCellValue($col . $row++, $otc->amount_name); 
                    }
                    $col++;
                    $otcLastRow = $row;

                    $row = $beforeRow;
                    // contract period
                    foreach ($entry->contractPeriods as $contract) {
                        $sheet->setCellValue($col . $row++, $contract->name); 
                    }
                    $col++;

                    $row = $beforeRow;
                    $sheet->setCellValue($col++ . $row, $entry->notes);


                    if ($otcLastRow > $row) {
                        $row = $otcLastRow;
                    }

                    $row++;
                }
            },
        ];
    }

    
}
