<?php

namespace App\Exports;

use App\Donor;
use App\Exports\Sheets\DonationsSheet;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;

class DonationsExport implements WithMultipleSheets, WithEvents
{
    use Exportable, DefaultFormatting;

    private $donor;

    public function __construct(Donor $donor)
    {
        $this->donor = $donor;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        // TODO ignore empty sheets: if (count($donations) > 0) {
        return [
            new DonationsSheet(Carbon::now()->subYear()->year, $this->donor),
            new DonationsSheet(Carbon::now()->year, $this->donor),
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            BeforeWriting::class => function(BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();
                $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
            },
        ];
    }
}
