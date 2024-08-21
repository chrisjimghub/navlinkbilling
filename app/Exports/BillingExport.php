<?php

namespace App\Exports;

use App\Http\Controllers\Admin\FilterQueries\BillingFilterQueries;
use App\Models\Billing;
use Illuminate\Support\Carbon;
use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class BillingExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;
    use ExportHelper;
    use BillingFilterQueries;

    protected $title = 'Billings';

    protected function entries()
    {
        $entries = Billing::billingCrud()
                    ->join('accounts', 'billings.account_id', '=', 'accounts.id')
                    ->join('customers', 'accounts.customer_id', '=', 'customers.id')
                    ->orderBy('customers.last_name', 'asc')
                    ->orderBy('customers.first_name', 'asc')
                    ->select('billings.*');

        $entries = $this->billingFilterQueries($entries);

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

                $this->excelTitle($sheet);
                
                $this->setTextBold($sheet, 5);

                // Add "Billing Period" and "Particulars" headers in merged cells
                $sheet->setCellValue('E4', 'Billing Period');
                $sheet->setCellValue('L4', 'Particulars');

                // Merge cells for the main header
                $sheet->mergeCells('E4:H4'); // Merge the cells from E4 to H4 for the 'Billing Period'
                $sheet->mergeCells('L4:N4'); // Merge the cells from L4 to N4 for the 'Particulars'

                // Apply styling
                $sheet->getStyle('E4:H4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle('L4:N4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Define headers in row 5
                $headers = [
                    __('app.row_num'), 
                    __('app.customer_name'), 
                    __('app.planned_application'), 
                    __('app.type'), 
                    __('app.billing_date_start'), 
                    __('app.billing_date_end'),
                    __('app.billing_date_cut_off'), 
                    __('app.billing_date_change'), 
                    __('app.status'), 
                    __('app.billing_payment_method'), 
                    __('app.created'), 
                    __('app.billing_description'),
                    __('app.billing_deduction'), 
                    __('app.billing_amount')
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
                    $firstLoop = true;

                    $particularsColRowStart = ''; // =SUM(M44:N46)
                    $particularsColRowEnd = '';

                    foreach ($entry->particulars as $particular) {
                        $deduction = null;
                        $amount = null;

                        if ($particular['amount'] > 0) {
                            $amount = $particular['amount'];
                        } elseif ($particular['amount'] < 0) {
                            $deduction = $particular['amount'];
                        }

                        $col = 'A'; // Reset column for each row
                        

                        if ($firstLoop) {
                            $sheet->setCellValue($col++ . $row, $num++); 
                            $sheet->setCellValue($col++ . $row, $entry->account_name);
                            $sheet->setCellValue($col++ . $row, $entry->account_planned_application_details);
                            $sheet->setCellValue($col++ . $row, $entry->billingType->name);
                            $sheet->setCellValue($col++ . $row, $entry->date_start);
                            $sheet->setCellValue($col++ . $row, $entry->date_end);
                            $sheet->setCellValue($col++ . $row, $entry->date_cut_off);
                            $sheet->setCellValue($col++ . $row, $entry->date_change ? $entry->date_change->toDateString() : null);
                            $sheet->setCellValue($col++ . $row, $entry->billingStatus->name);
                            $sheet->setCellValue($col++ . $row, $entry->mode_of_payment);
                            $sheet->setCellValue($col++ . $row, $entry->created_at->toDateString());
                            $sheet->setCellValue($col++ . $row, $particular['description']);

                            $particularsColRowStart = $col . $row;
                            $this->setTextColor($sheet, $col . $row, 'FFFF0000');
                            $this->setCellNumberFormat($sheet, $col . $row);
                            $sheet->setCellValue($col++ . $row, $deduction);

                            $particularsColRowEnd = $col . $row;
                            $this->setTextColor($sheet, $col . $row, 'FF009900');
                            $this->setCellNumberFormat($sheet, $col . $row);
                            $sheet->setCellValue($col++ . $row, $amount);

                        } else {
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, null);
                            $sheet->setCellValue($col++ . $row, $particular['description']);
                            
                            $this->setTextColor($sheet, $col . $row, 'FFFF0000');
                            $this->setCellNumberFormat($sheet, $col . $row);
                            $sheet->setCellValue($col++ . $row, $deduction);

                            $particularsColRowEnd = $col . $row;
                            $this->setTextColor($sheet, $col . $row, 'FF009900');
                            $this->setCellNumberFormat($sheet, $col . $row);
                            $sheet->setCellValue($col++ . $row, $amount);
                        }

                        $firstLoop = false;
                        $row++;
                    }

                    // Add a total row
                    $col = 'A'; // Reset column for total row
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);
                    $sheet->setCellValue($col++ . $row, null);

                    // Apply bold font to the "Total Balance" cell
                    $this->setTextBold($sheet, $col . $row);
                    $sheet->setCellValue($col++ . $row, __('app.billing_total'));
                    
                    $tempCoordinate = $particularsColRowStart.':'.$particularsColRowEnd;

                    $this->setTextBold($sheet, $col . $row);
                    $this->setCellNumberFormat($sheet, $col . $row);
                    // $sheet->setCellValue($col++ . $row, $entry->total);
                    $sheet->setCellValue($col++ . $row, "=SUM(".$tempCoordinate.")");

                    $row++;
                    $row++;
                }
            },
        ];
    }
    
}
