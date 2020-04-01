<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;

abstract class BaseExport implements WithTitle, ShouldAutoSize, WithEvents
{
    use Exportable, DefaultFormatting;

    /**
     * Orientation
     */
    public string $orientation = 'portrait';

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

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->setupPage($sheet);
                $this->setupView($sheet);
                $this->applyStyles($sheet);
            },
        ];
    }

}
