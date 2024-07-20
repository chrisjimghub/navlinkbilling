<?php

namespace App\Exports;

use App\Models\Billing;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class BillingExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;

    protected $title = 'Billings';
    protected $rowCounter = 1;

    public function query()
    {
        return Billing::query();
    }

    public function headings(): array
    {
        // This array is used only to ensure headers are properly aligned
        return [
            ['#', 'Customer Name', 'Planned Application', 'Type', 'Date Start', 'Date End', 'Cut Off', 'Date Change', 'Status', 'Payment Method', 'Created At', 'Description', 'Deduction', 'Amount']
        ];
    }

    public function map($entry): array
    {
        $rows = [];

        $firstLoop = true;
        foreach ($entry->particulars as $particular) {

            $deduction = null;
            $amount = null;

            if ($particular['amount'] > 0) {
                $amount = $particular['amount'];
            } elseif ($particular['amount'] < 0) {
                $deduction = $particular['amount'];
            }

            if ($firstLoop) {
                $rows[] = [
                    $this->rowCounter++,
                    $entry->account_name,
                    $entry->account_planned_application_details,
                    $entry->billingType->name,
                    $entry->date_start,
                    $entry->date_end,
                    $entry->date_cut_off,
                    $entry->date_change,
                    $entry->billingStatus->name,
                    $entry->mode_of_payment,
                    $entry->created_at->toDateString(),
                    $particular['description'],
                    $deduction,
                    $amount,
                ];
            } else {
                $rows[] = [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $particular['description'],
                    $deduction,
                    $amount,
                ];
            }

            $firstLoop = false;
        }

        $rows[] = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'Total Balance',
            $entry->total,
        ];

        $rows[] = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // You can adjust the styles as needed
        $sheet->getStyle('5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }

    public function startCell(): string
    {
        return 'A5'; // Start data from cell A5
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setCellValue('B1', $this->title);
                $sheet->setCellValue('B2', 'Generated: ' . now());

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
            },
           
        ];
    }
}
