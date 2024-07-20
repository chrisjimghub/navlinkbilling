<?php

namespace App\Exports;

use App\Models\Billing;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CutOffAccountExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnFormatting,
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents
{
    use Exportable;

    protected $title = 'Cut Off Accounts';

    protected $rowCounter = 1;

    public function query()
    {
        return Billing::
                cutOffAccounts()
                ->orderBy('date_cut_off', 'asc');
    }

    public function headings(): array
    {
        return [
            '#',
            __('app.widgets.account_name'),
            __('app.widgets.planned_app'),
            __('app.widgets.sub'),
            __('app.widgets.coordinates'),
            __('app.widgets.date_cut_off'),
            __('app.billing_total'),
        ];
    }

    public function map($entry): array
    {
        return [
            $this->rowCounter++,
            $entry->account_name,
            $entry->account_planned_application_details,
            $entry->account_subscription_name,
            $entry->real_account_google_coordiantes,
            Carbon::parse($entry->date_cut_off)->format(dateHumanReadable()),
            $entry->total,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply number format to the 7th column (G) for all rows
        $sheet->getStyle('G:G')->getNumberFormat()->setFormatCode('#,##0.00');
        

        // Make the first row bold
        $sheet->getStyle('4')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'C' => NumberFormat::FORMAT_CURRENCY_EUR_INTEGER,
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        
        return [
            AfterSheet::class    => function(AfterSheet $event){
                $event->sheet->setCellValue('B1', $this->title);
                $event->sheet->setCellValue('B2', 'Generated: '. carbonNow());
                

            },
        ];
    }

}
