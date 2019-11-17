<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;

trait DefaultFormatting
{
    protected function setupSpreadsheet(Spreadsheet $spreadsheet) {
        // Creator
        $spreadsheet->getProperties()->setCreator(env('APP_NAME'));
    
        // Default font
        $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
    }

    protected function setupPage(Worksheet $sheet) {
        // Orientation
        if ($this->orientation == 'landscape') {
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        } else {
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        }

        // Paper size
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

        // Margins
        if ($this->margins != null) {
            $sheet->getPageMargins()->setTop($this->margins);
            $sheet->getPageMargins()->setRight($this->margins);
            $sheet->getPageMargins()->setLeft($this->margins);
            $sheet->getPageMargins()->setBottom($this->margins);
        }

        // Fit to page width
        $sheet->getPageSetup()->setFitToWidth($this->fitToWidth);
        $sheet->getPageSetup()->setFitToHeight($this->fitToHeight);

        // Centering
        //$sheet->getPageSetup()->setHorizontalCentered(true);
        //$sheet->getPageSetup()->setVerticalCentered(false);

        // Header: Centered: sheet name
        $sheet->getHeaderFooter()->setOddHeader('&C&A');

        // Footer: Left: date, right: current page / number of pages
        $sheet->getHeaderFooter()->setOddFooter('&L&D&R&P / &N');

        // Print header row on each page
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
    }

    protected function setupView(Worksheet $sheet) {
        // Freeze first line
        $sheet->freezePane('B2');
        
        // Auto-filter
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    protected function applyStyles(Worksheet $sheet) {
        // Styling of header row
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')
            ->getFont()
            ->setBold(true);

        // Borders
        $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_MEDIUM);
    }
}
