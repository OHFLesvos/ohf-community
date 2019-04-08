<?php

namespace Modules\Fundraising\Exports;

use App\Exports\DefaultFormatting;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Exports\Sheets\DonationsSheet;

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
        $sheets = [];

        $last_year = Carbon::now()->subYear()->year;
        if ($this->donor->donations()->whereYear('date', $last_year)->count() > 0) {
            $sheets[] = new DonationsSheet($last_year, $this->donor);
        }

        $this_year = Carbon::now()->year;
        if ($this->donor->donations()->whereYear('date', $this_year)->count() > 0) {
            $sheets[] = new DonationsSheet($this_year, $this->donor);
        }

        return $sheets;
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
