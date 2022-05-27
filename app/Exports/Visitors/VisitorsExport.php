<?php

namespace App\Exports\Visitors;

use App\Exports\DefaultFormatting;
use App\Exports\Visitors\Sheets\VisitorCheckInsExport;
use App\Exports\Visitors\Sheets\VisitorDataExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;

class VisitorsExport implements WithMultipleSheets, WithEvents
{
    use Exportable, DefaultFormatting;

    public function sheets(): array
    {
        return [
            new VisitorDataExport,
            new VisitorCheckInsExport,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();
                $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
            },
        ];
    }
}
