<?php

namespace App\Exports;

use App\Exports\Traits\ExportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class BaseExport implements 
    WithCustomStartCell,
    ShouldAutoSize,
    WithEvents,
    WithStyles
{
    use Exportable;
    use ExportHelper;

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
        return [];
    }

    // @laravel excel, cant rename or the implement/interface will cause error
    public function styles(Worksheet $sheet)
    {
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
