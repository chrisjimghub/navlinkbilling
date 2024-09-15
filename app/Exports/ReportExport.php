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
    protected $monthYear;
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

        $this->monthYear = "$month $this->year";

        $this->expenseTitle = 'Expenses '.$this->monthYear;
        $this->collectionTitle = 'Collections '.$this->monthYear;

        $this->expenseEntriesTotalCount = $this->expenseEntries()->count();
        $this->collectionEntriesTotalCount = $this->collectionEntries()->count();
    }

    public function collectionEntries()
    {
        $entries = [];
        $entries = array_merge($entries, $this->billingCrudEntries()); 
        $entries = array_merge($entries, $this->hotspotVouchersCrudEntries()); 
        $entries = array_merge($entries, $this->salesCrudEntries()); 
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
        $uniqueCategories = $this->collectionEntries()->pluck('category')->unique();

        // dd($uniqueCategories);

        return $uniqueCategories;
    }

    public function expenseCategories()
    {
        $uniqueCategories = $this->expenseEntries()->map(function ($expense) {
            return $expense->category->name;
        })->unique();

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
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $this->excelTitle($sheet);
                $this->setTextBold($sheet, 5);
                $this->renameSheet($sheet, $this->monthYear);

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
                
                $highestRow = 1;

                $row = 6; 
                $startRow = $row;
                foreach ($this->expenseCategories() as $category) {
                    $col = 'B';
                    $sheet->setCellValue($col.$row, $category);

                    $sumIf = "=SUMIF('".$this->expenseTitle."'!E".$startRow.":E".($this->expenseEntriesTotalCount + $startRow).",\"=\"&B".$row.",'".$this->expenseTitle."'!F".$startRow.":F".($this->expenseEntriesTotalCount + $startRow).")";
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    $this->setCellNumberFormat($sheet, $col . $row);
                    $row++;
                }
                
                if ($row > $highestRow) {
                    $highestRow = $row;
                }

                $row = 6; 
                foreach ($this->collectionCategories() as $category) {
                    $col = 'D';
                    $sheet->setCellValue($col.$row, $category);
                    // TODO:: colelction category sub total is zero
                    $sumIf = "=SUMIF('".$this->collectionTitle."'!E".$startRow.":E".($this->collectionEntriesTotalCount + $startRow).",\"=\"&B".$row.",'".$this->collectionTitle."'!F".$startRow.":F".($this->collectionEntriesTotalCount + $startRow).")";
                    $sheet->setCellValue(++$col.$row, $sumIf);
                    $this->setCellNumberFormat($sheet, $col . $row);
                    $row++;
                }
                
                if ($row > $highestRow) {
                    $highestRow = $row;
                }




                $this->styles($sheet);
            },
        ];
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
