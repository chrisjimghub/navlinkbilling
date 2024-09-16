<?php 

namespace App\Exports\Traits;

use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExportHelper
{
    // auto width resize is in base class export
    protected function setDefaultZoomLevel(Worksheet $sheet, int $zoomLevel): void
    {
        // Ensure zoom level is between 10 and 400
        if ($zoomLevel < 10 || $zoomLevel > 400) {
            throw new \InvalidArgumentException('Zoom level must be between 10 and 400.');
        }

        // Get the sheet view and set the zoom scale
        $sheetView = $sheet->getSheetView();
        $sheetView->setZoomScale($zoomLevel);
    }

    protected function formatAsAccountingPhp(Worksheet $sheet, string $cellCoordinate): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'numberFormat' => [
                'formatCode' => '_-"₱"* #,##0.00_-;[Red]_- "₱"* #,##0.00_-;_- "₱"* "-"??_-;_-@_-'
            ],
        ]);
    }

    protected function setPageSize(Worksheet $sheet, string $size): void
    {
        // Define paper size mappings
        $paperSizes = [
            'A3' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3,
            'A4' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4,
            'A5' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A5,
            'B4' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_B4,
            'B5' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_B5,
            'Executive' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_EXECUTIVE,
            'Folio' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO,
            'Legal' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL,
            'Letter' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER,
            'Tabloid' => \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_TABLOID,
            // Add more paper sizes as needed
        ];

        // Check if the provided size exists in the array, and set it
        if (array_key_exists($size, $paperSizes)) {
            $sheet->getPageSetup()->setPaperSize($paperSizes[$size]);
        } else {
            throw new \InvalidArgumentException('Invalid paper size provided.');
        }
    }

    protected function enablePageBreakView(Worksheet $sheet): void
    {
        // Enable page break view in the worksheet
        $sheet->setSheetView(
            $sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW)
        );
    }

    protected function disableGridlines(Worksheet $sheet): void
    {
        // Disable gridlines for the worksheet
        $sheet->setShowGridlines(false);
    }

    protected function setGlobalFontStyle(Worksheet $sheet, $fontName): void
    {
        // Apply only the font style globally to the worksheet
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'font' => [
                'name' => $fontName, // Set the font style
            ],
        ]);
    }

    protected function setTextAlignment(Worksheet $sheet, $cellRange, $alignment): void
    {
        // Map alignment strings to PhpSpreadsheet constants
        $alignmentMap = [
            'left' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'right' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            'center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'justify' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
        ];

        // Set the alignment if valid, default to left if invalid
        $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(
            $alignmentMap[$alignment] ?? \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
        );
    }

    protected function setColumnWidth(Worksheet $sheet, $column, $width): void
    {
        // Set the width of the specified column
        $sheet->getColumnDimension($column)->setWidth($width);
    }

    protected function setTextSize(Worksheet $sheet, $cellCoordinate, $size): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
            'font' => [
                'size' => $size,
            ],
        ]);
    }
    
    protected function centerText(Worksheet $sheet, $cellCoordinate): void
    {
        $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

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
    protected function fillCellColor(Worksheet $sheet, $cellCoordinate, $color): void
    {
        $sheet->getStyle($cellCoordinate)->applyFromArray([
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