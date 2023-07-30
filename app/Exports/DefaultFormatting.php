<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DefaultFormatting
{
    /**
     * Orientation
     */
    public PageOrientation $orientation = PageOrientation::Portrait;

    /**
     * Margins
     */
    public ?float $margins = null;

    /**
     * Fit to width
     */
    public int $fitToWidth = 0;

    /**
     * Fit to height
     */
    public int $fitToHeight = 0;

    /**
     * Paper size
     */
    public PaperSize $paperSize = PaperSize::A4;

    protected function setupSpreadsheet(Spreadsheet $spreadsheet)
    {
        // Creator
        $spreadsheet->getProperties()->setCreator(config('app.name'));

        // Default font
        $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
    }

    protected function setupPage(Worksheet $sheet)
    {
        // Orientation
        $sheet->getPageSetup()->setOrientation($this->orientation == PageOrientation::Landscape
            ? PageSetup::ORIENTATION_LANDSCAPE
            : PageSetup::ORIENTATION_PORTRAIT
        );

        // Paper size
        $sheet->getPageSetup()->setPaperSize(match ($this->paperSize) {
            PaperSize::Letter => PageSetup::PAPERSIZE_LETTER,
            default => PageSetup::PAPERSIZE_A4,
        });

        // Margins
        if ($this->margins !== null) {
            $sheet->getPageMargins()->setTop($this->margins);
            $sheet->getPageMargins()->setRight($this->margins);
            $sheet->getPageMargins()->setLeft($this->margins);
            $sheet->getPageMargins()->setBottom($this->margins);
        }

        // Fit to page width
        $sheet->getPageSetup()->setFitToWidth($this->fitToWidth);
        $sheet->getPageSetup()->setFitToHeight($this->fitToHeight);

        // Header: Centered: sheet name
        $sheet->getHeaderFooter()->setOddHeader('&C&A');

        // Footer: Left: date, right: current page / number of pages
        $sheet->getHeaderFooter()->setOddFooter('&L&D&R&P / &N');

        // Print header row on each page
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
    }

    protected function setupView(Worksheet $sheet)
    {
        // Freeze first line
        $sheet->freezePane('B2');

        // Auto-filter
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    protected function applyStyles(Worksheet $sheet)
    {
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
