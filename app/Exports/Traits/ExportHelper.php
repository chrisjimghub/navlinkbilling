<?php 

namespace App\Exports\Traits;

use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExportHelper
{
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

    protected function setTextLinkFormat(Worksheet $sheet, $cellCoordinate): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'font' => [
                'color' => ['argb' => 'FF0000FF'], // Blue color
                'underline' => Font::UNDERLINE_SINGLE,
            ],
        ]);
    }

    protected function excelTitle(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A1', $this->title);
        $sheet->setCellValue('A2', 'Generated: '. carbonNow());
        
    }
}
