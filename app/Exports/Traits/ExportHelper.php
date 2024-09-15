<?php 

namespace App\Exports\Traits;

use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExportHelper
{
    // auto width resize is in base class export
    
    // Method to rename a sheet
    protected function renameSheet(Worksheet $sheet, $newName): void
    {
        $sheet->setTitle($newName);
    }


    // Method to hide a specific column
    protected function hideColumn(Worksheet $sheet, $columnLetter): void
    {
        $sheet->getColumnDimension($columnLetter)->setVisible(false);
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

    // Method to set cell background color
    protected function fillCellColor(Worksheet $sheet, $cellRange, $color): void
    {
        $sheet->getStyle($cellRange)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => $color,
                ],
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

    protected function convertColumnFormatIntoText(Worksheet $sheet)
    {
        // Apply text format to all columns from 'A' to the highest column
        $highestColumn = $sheet->getHighestColumn();
                
        // Apply text format to each column from 'A' to the highest column
        $sheet->getStyle('A:' . $highestColumn)
              ->getNumberFormat()
              ->setFormatCode(NumberFormat::FORMAT_TEXT);
    }

    // Reusable method to create and populate a hidden sheet
    protected function createHiddenSheet(Worksheet $sheet, $sheetName, $data)
    {
        $spreadsheet = $sheet->getParent();
        $sheet = new Worksheet($spreadsheet, $sheetName);
        $spreadsheet->addSheet($sheet);

        // Populate the hidden sheet with data
        $row = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item);
            $row++;
        }

        // Hide the sheet
        $sheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
    }

    protected function listDataValidation(Worksheet $sheet, $sheetName = 'Customers', $columnLetter = 'A', $listUptoRow = 100)
    {
        // Create a named range for the customer names
        $spreadsheet = $sheet->getParent();
        $hiddenSheet = $spreadsheet->getSheetByName($sheetName);
        $lastHiddenSheetRow = $hiddenSheet->getHighestRow();
        
        for ($i = 2; $i < $listUptoRow; $i++) {
            $cell = $sheet->getCell($columnLetter.$i);
            $validation = $cell->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(true) // Allow blank cells
                ->setShowInputMessage(true)
                ->setShowErrorMessage(false) // Disable error messages
                ->setShowDropDown(true)
                ->setFormula1('='.$sheetName.'!$A$1:$A$'.$lastHiddenSheetRow); // Use the named range for data validation
            $cell->setDataValidation($validation);
        }
    }
}
