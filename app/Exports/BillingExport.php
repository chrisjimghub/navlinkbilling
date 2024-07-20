<?php

namespace App\Exports;

use App\Models\Billing;
use Illuminate\Support\Carbon;
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

    protected $title = 'Billings';

    protected function entries()
    {
        // this request inputs are already validated in Export operation
        $status = request()->input('status');
        $type = request()->input('type');
        $period = request()->input('period');

        $entries = Billing::query();

        if ($status) {
            if ($status == 1) {
                $entries = $entries->paid();
            } elseif ($status == 2) {
                $entries = $entries->unpaid();
            }
        }

        if ($type) {
            if ($type == 1) {
                $entries = $entries->installment();
            } elseif ($type == 2) {
                $entries = $entries->monthly();
            }
        }

        if ($period) {
            $dates = explode('-', $period);
            $dateStart = Carbon::parse($dates[0]);
            $dateEnd = Carbon::parse($dates[1]);
            $entries->withinBillingPeriod($dateStart, $dateEnd);
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
                    '#', 'Customer Name', 'Planned Application', 'Type', 'Date Start', 'Date End',
                    'Cut Off', 'Date Change', 'Status', 'Payment Method', 'Created At', 'Description',
                    'Deduction', 'Amount'
                ];

                // Write headers to the sheet
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '5', $header);
                    $col++;
                }
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
                            $sheet->setCellValue($col++ . $row, $num++); // Adjust this field based on your model
                            $sheet->setCellValue($col++ . $row, $entry->account_name);
                            $sheet->setCellValue($col++ . $row, $entry->account_planned_application_details);
                            $sheet->setCellValue($col++ . $row, $entry->billingType->name);
                            $sheet->setCellValue($col++ . $row, $entry->date_start);
                            $sheet->setCellValue($col++ . $row, $entry->date_end);
                            $sheet->setCellValue($col++ . $row, $entry->date_cut_off);
                            $sheet->setCellValue($col++ . $row, $entry->date_change);
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
                    $sheet->setCellValue($col++ . $row, 'Total Balance');
                    
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

    protected function setCellNumberFormat($sheet, $cellCoordinate)
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'numberFormat' => [
                'formatCode' => '#,##0.00', // Number format with thousand separator and two decimal places
            ],
        ]);
    }

    protected function setTextBold(Worksheet $sheet, $cellCoordinate): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }

    // Method to set text color
    protected function setTextColor(Worksheet $sheet, $cellCoordinate, $color): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'font' => [
                'color' => ['argb' => $color],
            ],
        ]);
    }
}
