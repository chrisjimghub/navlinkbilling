<?php

namespace App\Exports;

use App\Exports\Traits\ExportHelper;
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
    use ExportHelper;

    protected $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

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
            __('app.row_num'),
            __('app.customer_name'),
            __('app.dashboard.planned_app'),
            __('app.dashboard.sub'),
            __('app.dashboard.coordinates'),
            __('app.dashboard.date_cut_off'),
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
                $sheet = $event->sheet->getDelegate(); // Get PhpSpreadsheet object
                $this->excelTitle($sheet);
                

            },
        ];
    }

}
