<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

abstract class BaseExport implements WithTitle, ShouldAutoSize, WithEvents
{
    use Exportable;

    private $orientation = 'portrait';
    private $margins = null;

    public function setOrientation(string $orientation) {
        $this->orientation = $orientation;
    }

    public function setMargins(float $margins) {
        $this->margins = $margins;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                // Creator
                $event->writer->getDelegate()->getProperties()->setCreator(env('APP_NAME'));
                
                // Default font
                $event->writer->getDelegate()->getDefaultStyle()->getFont()->setSize(9);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->setupPage($sheet);
                $this->setupView($sheet);
                $this->applyStyles($sheet);
            },
        ];
    }

    private function setupPage(Worksheet $sheet) {
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
        // $sheet->getPageSetup()->setFitToWidth(1);
        // $sheet->getPageSetup()->setFitToHeight(0);

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

    private function setupView(Worksheet $sheet) {
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
