<?php

namespace App\Exports;

use App\Models\Billing;
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

                    // $sheet->setCellValue($col++ . $row, $num++); 
                    $row++;
                }
            },
        ];
    }

    public function salesCollectionsSheet($sheet)
    {
        $spreadsheet = $sheet->getParent();
        $sheetTitle = 'Collections'.$this->monthYear;
        $sheet = new Worksheet($spreadsheet, $sheetTitle);
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
            // $sheet->setCellValue($col . '5', $header);
            $col++;
        }

        // Get the month and year from the request
        $month = request()->input('month');
        $year = request()->input('year');

        // Initialize the query
        $query = Billing::with('account')->whereIn('billing_type_id', [1, 2]);

        // TODO FIX:: use snapshot for installed_date for whereclause
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

        $entries = [];
        foreach ($billings as $billing) {
            foreach ($billing->particulars as $particular) {
                $date = null;
                $by = null;
                $description = $particular['description'];
                $amount = $particular['amount'];

                if ($billing->billing_type_id == 1) {
                    // installation fee
                    $date = $billing->account_snapshot['account']['installed_date'];
                }elseif ($billing->billing_type_id == 2) {
                    // monthly fee
                    $date = $billing->date_end;
                }

                if ($billing->paymongo_reference_number != null) {
                    $by = 'Online Payment';
                }else {
                    $by = $billing->lastEditedBy->name;
                }


                // TODO:: group deductions and deduct it to monthly fee?
                // TODO:: group monthly fee, pro rated as P2p/Fiber Monthly Billing
                // TODO:: reminder: there is also Pro-rated in service adjustment(negtaive value)

                $entries[] = [
                    'billing_id' => $billing->id,
                    'date' => $date,
                    'by' => $by,
                    'category' => $description,
                    'amount' => $amount,
                ];
            }//endForeach $partiulars
        }//endForeach $billings

        // dd(
        //     $billings->toArray()
        // );

        dd($entries);

        // $grouped = $billings->flatMap(function ($billing) {
        //     return collect($billing->particulars)->map(function ($particular) use ($billing) {
        //         $description = $particular['description'];
        //         $amount = $particular['amount'];
        
        //         // Debugging output (optional, you can remove this later)
        //         // dump("Description: $description, Amount: $amount");
        
        //         // Determine the base group name for "Monthly Fee" or "Pro Rated" descriptions
        //         if (
        //             stripos($description, 'Monthly Fee') !== false 
        //             || stripos($description, 'Monthly-Fee') !== false
        //             || stripos($description, 'Pro Rated') !== false 
        //             || stripos($description, 'Pro-Rated') !== false
        //             || stripos($description, 'ProRated') !== false
        //         ) {
        //             if ($billing->account->subscription_id == 1) { // p2p
        //                 $description = 'P2P Monthly Billing';
        //             } elseif ($billing->account->subscription_id == 2) { // fiber
        //                 $description = 'Fiber Monthly Billing';
        //             } else {
        //                 $description = 'Other Monthly Billing'; // Default for other subscription_ids
        //             }
        //         } elseif (stripos($description, 'Service Interruptions') !== false) {
        //             // Normalize "Service Interruptions" descriptions
        //             $description = 'Service Interruptions';
        //         }
        
        //         return [
        //             'group' => $description,
        //             'amount' => $amount
        //         ];
        //     });
        // })->groupBy(function ($item) {
        //     return $item['group'];
        // })->map(function ($group) {
        //     // Sum the 'amount' for each group
        //     return $group->sum('amount');
        // });
        
        // Debug output
        // dd($grouped);
        
    
        

        // TODO:: Hotspot Vouchers
        // TODO:: Wifi Harvest
        // TODO:: Sales



        $entries = [];


        $row = 6; 
        $num = 1;
        foreach ($entries as $entry) {
            $col = 'A'; // reset col every row

            // $sheet->setCellValue($col++ . $row, $num++);

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
