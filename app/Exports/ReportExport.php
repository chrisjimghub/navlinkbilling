<?php

namespace App\Exports;

use App\Models\Sales;
use App\Models\Billing;
use App\Models\Expense;
use App\Exports\BaseExport;
use App\Models\HotspotVoucher;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport extends BaseExport {

    protected $title = 'Reports';

    protected $month;
    protected $year;
    protected $monthYear = '';
    protected $expenseTitle;
    protected $collectionTitle;
    protected $expenseEntriesTotalCount;
    protected $collectionEntriesTotalCount;
    
    public function __construct()
    {
        $this->month = request()->month;
        $this->year = request()->year;

        $month = null;
        if ($this->month) {
            $month = monthText($this->month);
        }
        
        if ($month) {
            $this->monthYear .= $month;
        }

        if ($this->year) {
            if ($month) {
                $this->monthYear .= ' ';
            }
            $this->monthYear .= $this->year;
        }

        $this->expenseTitle = 'Expenses '.$this->monthYear;
        $this->collectionTitle = 'Collections '.$this->monthYear;

        $this->expenseEntriesTotalCount = $this->expenseEntries()->count();
        $this->collectionEntriesTotalCount = $this->collectionEntries()->count();
    }

    public function collectionEntries()
    {
        $entries = [];

        $entries = array_merge($entries, $this->salesCrudEntries()); 
        $entries = array_merge($entries, $this->billingCrudEntries()); 
        $entries = array_merge($entries, $this->hotspotVouchersCrudEntries()); 
        $entries = array_merge($entries, $this->wifiHarvestCrudEntries()); 
        $entries = collect($entries)->sortBy([
            ['date', 'asc'],
            ['category', 'asc']
        ]);

        return $entries;
    }

    public function expenseEntries()
    {
        $entries = Expense::query();

        if ($this->month) {
            $entries->whereMonth('date', $this->month);
        }

        if ($this->year) {
            $entries->whereYear('date', $this->year);
        }

        return $entries->orderBy('date', 'asc')->get();
    }

    public function collectionCategories()
    {
        $uniqueCategories = $this->collectionEntries()->pluck('category')->unique()->sort();

        // dd($uniqueCategories);

        return $uniqueCategories;
    }

    public function expenseCategories()
    {
        $uniqueCategories = $this->expenseEntries()->map(function ($expense) {
            return $expense->category->name;
        })->unique()->sort();

        // dd($uniqueCategories);

        return $uniqueCategories;
    }

    public function startCell(): string
    {
        return 'A5'; // Start data from cell A5
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $this->renameSheet($sheet, 'Summary '.$this->monthYear);
                $this->expensesSheet($sheet);
                $this->salesCollectionsSheet($sheet);
                $this->headers($sheet);

                $highestRow = 1;

                $row = 4; 
                $startRow = $row + 1;
                foreach ($this->expenseCategories() as $category) {
                    $col = 'D';
                    $sheet->setCellValue($col.$row, $category);
                    // $sumIf = "=SUMIF('".$this->expenseTitle."'!E".$startRow.":E".($this->expenseEntriesTotalCount + $startRow).",\"=\"&".$col.$row.",'".$this->expenseTitle."'!F".$startRow.":F".($this->expenseEntriesTotalCount + $startRow).")";
                    $sumIf = $this->flowsSumIfFormula(
                        $this->expenseTitle, 
                        $startRow, 
                        ($this->expenseEntriesTotalCount + $startRow), 
                        $col.$row
                    );
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    $row++;
                }
                
                $expenseLastRow = $row;

                if ($row > $highestRow) {
                    $highestRow = $row;
                }

                $row = 4; 
                foreach ($this->collectionCategories() as $category) {
                    $col = 'I';
                    $sheet->setCellValue($col.$row, $category);
                    // $sumIf = "=SUMIF('".$this->collectionTitle."'!E".$startRow.":E".($this->collectionEntriesTotalCount + $startRow).",\"=\"&".$col.$row.",'".$this->collectionTitle."'!F".$startRow.":F".($this->collectionEntriesTotalCount + $startRow).")";
                    $sumIf = $this->flowsSumIfFormula(
                        $this->collectionTitle, 
                        $startRow, 
                        ($this->collectionEntriesTotalCount + $startRow), 
                        $col.$row
                    );
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    $row++;
                }
                
                $collectionLastRow = $row;

                if ($row > $highestRow) {
                    $highestRow = $row;
                }

                while ($expenseLastRow < $highestRow) {
                    $col = 'D';
                    $row = $expenseLastRow;
                    $sumIf = $this->flowsSumIfFormula(
                        $this->expenseTitle, 
                        $startRow, 
                        ($this->expenseEntriesTotalCount + $startRow), 
                        $col.$row
                    );
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    
                    $expenseLastRow++;
                }

                while ($collectionLastRow < $highestRow) {
                    $col = 'D';
                    $row = $collectionLastRow;
                    $sumIf = $this->flowsSumIfFormula(
                        $this->collectionTitle, 
                        $startRow, 
                        ($this->collectionEntriesTotalCount + $startRow), 
                        $col.$row
                    );
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    
                    $collectionLastRow++;
                }

                $sheet->setCellValue('D'.$highestRow, 'Total');
                $sheet->setCellValue('I'.$highestRow, 'Total');
                
                $sheet->setCellValue('E'.$highestRow, '=SUM(E4:E'.($highestRow - 1).')');
                $sheet->setCellValue('J'.$highestRow, '=SUM(J4:J'.($highestRow - 1).')');

                // col A
                $sheet->setCellValue('A4', '=E'.$highestRow);
                $sheet->setCellValue('A6', '=J'.$highestRow);

                $totalCashRow = ($highestRow - 6) / 2;
                $totalCashRow = (int) $totalCashRow;

                $sheet->setCellValue('A'.$totalCashRow, '=A6-A4');

                $this->excelFormat($sheet, $highestRow, $totalCashRow);
            },
        ];
    }

    public function flowsSumIfFormula($sheetTitle, $startRow, $endRow, $criteriaCoordinate)
    {
        return "=SUMIF('".$sheetTitle."'!E".$startRow.":E".$endRow.",\"=\"&".$criteriaCoordinate.",'".$sheetTitle."'!F".$startRow.":F".$endRow.")";
    }

    public function excelFormat($sheet, $highestRow, $totalCashRow)
    {
        $sheet->getColumnDimension('A')->setAutoSize(false); //override the auto set width in BaseExport class style method

        $this->setGlobalFontStyle($sheet, 'Century Gothic');

        // adjust width
        $this->setColumnWidth($sheet, 'A', 39.07);
        $this->setColumnWidth($sheet, 'B', 1.36);
        $this->setColumnWidth($sheet, 'C', 1.36);
        $this->setColumnWidth($sheet, 'D', 50.22);
        $this->setColumnWidth($sheet, 'E', 19.94);
        $this->setColumnWidth($sheet, 'F', 2.36);
        $this->setColumnWidth($sheet, 'G', 2.22);
        $this->setColumnWidth($sheet, 'H', 15.22);
        $this->setColumnWidth($sheet, 'I', 36.8);
        $this->setColumnWidth($sheet, 'J', 24.51);
        $this->setColumnWidth($sheet, 'K', 2.36);
        $this->setColumnWidth($sheet, 'L', 1.8);

        // row height
        $this->setRowHeight($sheet, 1, 42); 
        for ($i = 2; $i <= $highestRow; $i++) {
            $this->setRowHeight($sheet, $i, 22.5); 
        }

        // merge cells
        foreach ([
            'A1:K1',
            'D2:K2',
            'D3:E3',
            'I3:J3',
        ] as $range) {
            $sheet->mergeCells($range); 
            $this->centerText($sheet, $range);
        }
        
        // numeric col
        $this->formatAsAccountingPhp($sheet, 'A2:A'.$highestRow, '"₱"#,##0.00'); 
        $this->formatAsAccountingPhp($sheet, 'E'); 
        $this->formatAsAccountingPhp($sheet, 'J');

        // align
        $this->setTextAlignment($sheet, 'D', 'right');
        $this->setTextAlignment($sheet, 'I', 'right');
        $this->setTextAlignment($sheet, 'D2', 'center');
        $this->setTextAlignment($sheet, '3', 'center');
        $this->setTextAlignment($sheet, 'E', 'center');
        $this->setTextAlignment($sheet, 'J', 'center');
        $this->setTextAlignment($sheet, 'A', 'center');

        // font color
        $this->setTextColor($sheet, 'A1', 'FF4e80be');
        $this->setTextColor($sheet, 'D2', 'FFffffff');
        $this->setTextColor($sheet, '3', 'FF000000');

        $this->setTextColor($sheet, 'E', 'FFf7ffff');
        $this->setTextColor($sheet, 'E'.$highestRow, 'FFf7ffff');
        $this->setTextColor($sheet, 'J', 'FF070304');
        $this->setTextColor($sheet, 'J'.$highestRow, 'FF070304');

        $this->setTextColor($sheet, 'D4:D'.$highestRow, 'FF7c99ba');
        $this->setTextColor($sheet, 'I4:I'.$highestRow, 'FF7c99ba');

        $this->setTextColor($sheet, $highestRow, 'FF000000');

        // fill color
        $this->fillCellColor($sheet, 'A2:B2', 'FF366092');
        
        $this->fillCellColor($sheet, 'D2', 'FF4f81bc');
        
        $this->fillCellColor($sheet, 'D3:F3', 'FFb8cce4');
        $this->fillCellColor($sheet, 'I3:K3', 'FFb8cce4');
        
        $this->fillCellColor($sheet, 'C2:C'.$highestRow, 'FFdbe4f1');
        $this->fillCellColor($sheet, 'F4:F'.$highestRow, 'FFdbe4f1');
        $this->fillCellColor($sheet, 'K4:K'.$highestRow, 'FFdbe4f1');
        
        $this->fillCellColor($sheet, 'E4:E'.$highestRow, 'FF95b3d7');
        $this->fillCellColor($sheet, 'J4:J'.$highestRow, 'FF95b3d7');

        $this->fillCellColor($sheet, 'E'.$highestRow, 'FFdce6f0');
        $this->fillCellColor($sheet, 'J'.$highestRow, 'FFdce6f0');

        $this->fillCellColor($sheet, 'B3:B'.$highestRow, 'FF95b3d7');
        $this->fillCellColor($sheet, 'A3', 'FF95b3d7');
        $this->fillCellColor($sheet, 'A5', 'FF95b3d7');
        $this->fillCellColor($sheet, 'A7', 'FF95b3d7');
        $this->fillCellColor($sheet, 'A'.$highestRow, 'FF95b3d7');

        // font size
        $this->setTextSize($sheet, 'A', 12);
        $this->setTextSize($sheet, '1', 24);
        $this->setTextSize($sheet, '2', 16);
        $this->setTextSize($sheet, 'D3:I3', 14);
        $this->setTextSize($sheet, 'A3', 12);
        $this->setTextSize($sheet, 'D'.$highestRow, 12);
        $this->setTextSize($sheet, 'I'.$highestRow, 12);
        $this->setTextSize($sheet, 'E'.$highestRow, 14);
        $this->setTextSize($sheet, 'J'.$highestRow, 14);
        $this->setTextSize($sheet, 'A'.$totalCashRow, 18);

        // bold
        $this->setTextBold($sheet, '1');
        $this->setTextBold($sheet, '2');
        $this->setTextBold($sheet, '3');
        $this->setTextBold($sheet, 'A');
        $this->setTextBold($sheet, 'E');
        $this->setTextBold($sheet, 'J');
        $this->setTextBold($sheet, 'D'.$highestRow);
        $this->setTextBold($sheet, 'I'.$highestRow);

        // view settings
        $this->disableGridlines($sheet);
        $this->enablePageBreakView($sheet);

        // Page setup
        // Set page margins
        $margins = $sheet->getPageMargins();
        // Set header and footer margins
        $margins->setHeader(0.30);
        $margins->setFooter(0.30);
        // Set top, left, right, and bottom margins
        $margins->setTop(0.50);
        $margins->setLeft(0.00);
        $margins->setRight(0.00);
        $margins->setBottom(0.00);

        // Set page orientation to landscape
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set the worksheet to fit on one page
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1);
        
        $this->setPageSize($sheet, 'A4'); 

        // misc.
        $this->setDefaultZoomLevel($sheet, 70); 
    }

    public function headers($sheet)
    {
        $sheet->setCellValue('A1', __('NAVLINK TECHNOLOGY STATEMENT OF CASH FLOWS'));
        $sheet->setCellValue('D2', __('Cash Flows from Operating Activities for ').$this->monthYear);
        $sheet->setCellValue('D3', __('CASH OUTFLOWS (EXPENSES)'));
        $sheet->setCellValue('I3', __('CASH INFLOWS (SALES/COLLECTIONS)'));
        $sheet->setCellValue('A3', __('Total Expenses'));
        $sheet->setCellValue('A5', __('SALES/COLLECTIONS'));
        $sheet->setCellValue('A7', __('Cash at the end of the Period'));
    }

    public function salesCollectionsSheet($sheet)
    {
        $spreadsheet = $sheet->getParent();
        $sheet = new Worksheet($spreadsheet, $this->collectionTitle);
        $spreadsheet->addSheet($sheet);

        $this->hideColumn($sheet, 'B'); // Hide column B

        $this->excelTitle($sheet);
        $this->setTextBold($sheet, 5);

        // Define headers in row 5
        $headers = [
            __('app.row_num'), 
            __('Origin'), 
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

        $row = 6; 
        $num = 1;
        foreach ($this->collectionEntries() as $entry) {
            $col = 'A'; // reset col every row

            $sheet->setCellValue($col++ . $row, $num++);
            $sheet->setCellValue($col++ . $row, $entry['from']);
            $sheet->setCellValue($col++ . $row, $entry['date']);
            $sheet->setCellValue($col++ . $row, $entry['by']);
            $sheet->setCellValue($col++ . $row, $entry['category']);

            $this->setCellNumberFormat($sheet, $col . $row);
            $sheet->setCellValue($col++ . $row, $entry['amount']);
            
            if ($entry['by'] == null) {
                $this->fillCellColor($sheet, 'A'.$row.':'.$col.$row, 'FFFF0000'); // Fill range A1:D1 with red color
            }

            $row++;
        }

        $this->styles($sheet);
    }

    private function wifiHarvestCrudEntries()
    {
        $entries = [];

        $month = $this->month;
        $year = $this->year;

        // Initialize the query
        $query = Billing::with('account')->where('billing_type_id', 3);

        // Apply month and year filters conditionally
        if ($month) {
            $query->whereMonth('date_start', $month);
        }
        
        if ($year) {
            $query->whereYear('date_start', $year);
        }

        // Execute the query
        $billings = $query->get();

        foreach ($billings as $billing) {
            $by = null;

            if ($billing->payment_details) {
                $paymentDetails = $billing->payment_details;
                if (array_key_exists('paymongo_reference_number', $paymentDetails) && $paymentDetails['paymongo_reference_number']) {
                    $by = 'Online Payment';
                }
            }else {
                $by = $billing->lastEditedBy->name ?? null;
            }

            $category = 'Wifi Harvest';
            $entries[] = [
                'from' => $category,
                'date' => $billing->date_start,
                'by' => $by,
                'category' => $category,
                'amount' => $billing->total,
            ];
        }//endForeach $billings

        return $entries;
    }

    private function salesCrudEntries()
    {
        $entries = [];

        $month = $this->month;
        $year = $this->year;

        // Initialize the query
        $query = Sales::query();
        
        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        // Execute the query
        $records = $query->get();

        foreach ($records as $record) {
            $entries[] = [
                'from' => 'sales',
                'date' => $record->date,
                'by' => $record->receiver ? $record->receiver->name : null,
                'category' => $record->category ? $record->category->name : null,
                'amount' => $record->amount,
            ];
        }//endForeach $records

        return $entries;
    }

    private function hotspotVouchersCrudEntries()
    {
        $entries = [];

        $month = $this->month;
        $year = $this->year;

        // Initialize the query
        $query = HotspotVoucher::with('account');
        
        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        // Execute the query
        $records = $query->get();

        foreach ($records as $record) {
            $entries[] = [
                'from' => 'hotspot vouchers',
                'date' => $record->date,
                'by' => $record->receiver ? $record->receiver->name : null,
                'category' => $record->category ? $record->category->name : null,
                'amount' => $record->amount,
            ];
        }//endForeach $records

        return $entries;
    }

    private function billingCrudEntries()
    {
        $entries = [];

        $month = $this->month;
        $year = $this->year;

        // Initialize the query
        $query = Billing::with('account')->whereIn('billing_type_id', [1, 2]);

        // Apply month and year filters conditionally
        if ($month) {
            $query->where(function ($q) use ($month) {
                // For billing_type_id == 1 (using installed_date from the account_snapshot json column)
                $q->where(function ($q) use ($month) {
                    $q->where('billing_type_id', 1)
                      ->whereMonth('account_snapshot->account->installed_date', $month);
                })
                // For billing_type_id == 2 (using date_end from the billing model)
                ->orWhere(function ($q) use ($month) {
                    $q->where('billing_type_id', 2)
                      ->whereMonth('date_end', $month);
                });
            });
        }
        
        if ($year) {
            $query->where(function ($q) use ($year) {
                // For billing_type_id == 1 (using installed_date from the account_snapshot json column)
                $q->where(function ($q) use ($year) {
                    $q->where('billing_type_id', 1)
                      ->whereYear('account_snapshot->account->installed_date', $year);
                })
                // For billing_type_id == 2 (using date_end from the billing model)
                ->orWhere(function ($q) use ($year) {
                    $q->where('billing_type_id', 2)
                      ->whereYear('date_end', $year);
                });
            });
        }

        // Execute the query
        $billings = $query->get();

        foreach ($billings as $billing) {
            $particulars = [];
            foreach ($billing->particulars as $particular) {
                $description = $particular['description'];
                $amount = $particular['amount'];
                
                $particulars[] = [
                    'category' => $description,
                    'amount' => $amount,
                ];
            }//endForeach $partiulars

            $date = null;
            $by = null;
            $categoryPrefix = null;
            $typeInstallation = false;

            $by = $billing->lastEditedBy->name ?? null;

            if ($billing->billing_type_id == 1) {
                // installation fee
                $date = $billing->account_snapshot['account']['installed_date'];
                $typeInstallation = true;
            }elseif ($billing->billing_type_id == 2) {
                // monthly fee
                $date = $billing->date_end;
            }

            if ($billing->payment_details) {
                $paymentDetails = $billing->payment_details;
                if (array_key_exists('paymongo_reference_number', $paymentDetails) && $paymentDetails['paymongo_reference_number']) {
                    $by = 'Online Payment';
                }
            }

            if ($billing->account->subscription_id == 1) { // p2p
                $categoryPrefix = 'P2P ';
            } elseif ($billing->account->subscription_id == 2) { // fiber
                $categoryPrefix = 'Fiber ';
            }            

            // Group the categories and sum the amounts
            $groupedAndSummed = collect($particulars)
            ->groupBy(function ($item) use ($typeInstallation, $categoryPrefix) {
                if ($typeInstallation) {
                    return $categoryPrefix. 'Installation';
                }

                $monthlyKeywords = [
                    'monthly fee', 
                    'monthly-fee',
                ];

                // Convert category to lowercase for case-insensitive comparison
                $categoryLower = strtolower($item['category']);

                // Loop through the array of monthly-related keywords
                foreach ($monthlyKeywords as $keyword) {
                    if (str_contains($categoryLower, strtolower($keyword))) {
                        return $categoryPrefix.'Monthly Billing';
                    }
                }

                if (containsDayPatternAndProRated($categoryLower)) {
                    return $categoryPrefix.'Monthly Billing';
                }

                // Group all negative amounts under "Monthly Billing"
                if ($item['amount'] < 0) {
                    return $categoryPrefix.'Monthly Billing';
                }

                // Otherwise, keep the original category
                return $item['category'];
            })
            ->map(function ($group, $category) use ($date, $by) {
                return [
                    'from' => 'billings',
                    'date' => $date,
                    'by' => $by,
                    'category' => $category,
                    'amount' => $group->sum('amount'),
                ];
            })
            ->values(); // Reset array keys

            $entries = array_merge($entries, $groupedAndSummed->toArray());
        }//endForeach $billings

        // dd($entries);

        return $entries;
    }

    private function expensesSheet($sheet)
    {
        $spreadsheet = $sheet->getParent();
        $sheet = new Worksheet($spreadsheet, $this->expenseTitle);
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

        $row = 6; 
        $num = 1;
        foreach ($this->expenseEntries() as $entry) {
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
