<?php

namespace App\Exports\Fundraising;

use App\Exports\DefaultFormatting;
use App\Exports\Fundraising\Sheets\DonationsSheet;
use App\Exports\Fundraising\Sheets\DonationsWithDonorSheet;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;

class DonationsExport implements WithEvents, WithMultipleSheets
{
    use DefaultFormatting, Exportable;

    public function __construct(private ?Donor $donor = null, private bool $includeAddress = false, private ?int $year = null)
    {
    }

    private function getDonationsQuery()
    {
        return $this->donor != null
            ? $this->donor->donations()
            : Donation::query();
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->year !== null && $this->year > 0) {
            $years = collect([$this->year]);
        } else {
            $years = $this->getDonationsQuery()
                ->selectRaw('YEAR(date) as year')
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->pluck('year');
        }
        if ($years->count() > 0) {
            foreach ($years as $year) {
                if ($this->getDonationsQuery()->forYear($year)->count() > 0) {
                    $data = $this->getDonationsQuery()
                        ->forYear($year)
                        ->orderBy('date', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->get();
                    $sheets[] = $this->donor != null
                        ? new DonationsSheet($data, $year)
                        : new DonationsWithDonorSheet($data, $year, $this->includeAddress);
                }
            }
        } else {
            $data = collect();
            $sheets[] = $this->donor != null
                ? new DonationsSheet($data)
                : new DonationsWithDonorSheet($data, includeAddress: $this->includeAddress);
        }

        return $sheets;
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
