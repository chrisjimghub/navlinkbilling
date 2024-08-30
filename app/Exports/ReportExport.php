<?php

namespace App\Exports;

use App\Models\Expense;
use App\Exports\BaseExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport extends BaseExport {
    

    protected $title = 'Reports';

    protected $month;
    protected $year;
    protected $monthYear;
    
    public function __construct()
    {
        $this->month = request()->month;
        $this->year = request()->year;

        $month = null;
        if ($this->month) {
            $month = monthText($this->month);
        }

        $this->monthYear = "$month $this->year";
    }

    protected function entries()
    {
        return [];
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
                ];

                // Write headers to the sheet
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '5', $header);
                    $col++;
                }

                 // Freeze the first two columns (A and B)
                //  $sheet->freezePane('C6');

                $this->expensesSheet($sheet);
                $this->salesCollectionsSheet($sheet);
            },

            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $row = 6; // Start from row 6 for data
                $col = 'A'; // Starting column

                $num = 1;
                foreach ($this->entries() as $entry) {
                    $col = 'A'; // Reset column for each row

                    $sheet->setCellValue($col++ . $row, $num++); 
                    $row++;
                }
            },
        ];
    }

    public function salesCollectionsSheet($sheet)
    {
        $spreadsheet = $sheet->getParent();
        $sheet = new Worksheet($spreadsheet, 'Sales(Collection) '.$this->monthYear);
        $spreadsheet->addSheet($sheet);

        $this->excelTitle($sheet);
        $this->setTextBold($sheet, 5);

        // Define headers in row 5
        $headers = [
            __('app.row_num'), 
            __('app.date'), 
            __('app.receiver_paidthru'),
            __('app.category'),
            __('app.amount'),
        ];

        // Write headers to the sheet
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }

        $entries = [];

        // TODO:: Billing CRUD
        // TODO:: Hotspot Vouchers
        // TODO:: Wifi Harvest
        // TODO:: Sales

        




        $row = 6; 
        $num = 1;
        foreach ($entries as $entry) {
            $col = 'A'; // reset col every row

            $sheet->setCellValue($col++ . $row, $num++);

            // $this->setCellNumberFormat($sheet, $col . $row);
            // $sheet->setCellValue($col++ . $row, $entry->amount);
            
            $row++;
        }

        

        $this->styles($sheet);
    }

    public function expensesSheet($sheet)
    {
        $spreadsheet = $sheet->getParent();
        $sheet = new Worksheet($spreadsheet, 'Expenses '.$this->monthYear);
        $spreadsheet->addSheet($sheet);

        $this->excelTitle($sheet);
        $this->setTextBold($sheet, 5);

        // Define headers in row 5
        $headers = [
            __('app.row_num'), 
            __('app.date'), 
            __('app.description'),
            __('app.receiver'),
            __('app.category'),
            __('app.amount'),
        ];

        // Write headers to the sheet
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }

        $entries = Expense::whereMonth('date', $this->month)->whereYear('date', $this->year)->orderBy('date', 'asc')->get();

        $row = 6; 
        $num = 1;
        foreach ($entries as $entry) {
            $col = 'A'; // reset col every row

            $sheet->setCellValue($col++ . $row, $num++);
            $sheet->setCellValue($col++ . $row, $entry->date);
            $sheet->setCellValue($col++ . $row, $entry->description);
            $sheet->setCellValue($col++ . $row, $entry->receiver->name);
            $sheet->setCellValue($col++ . $row, $entry->category->name);

            $this->setCellNumberFormat($sheet, $col . $row);
            $sheet->setCellValue($col++ . $row, $entry->amount);
            
            $row++;
        }

        $this->styles($sheet);
    }
}
