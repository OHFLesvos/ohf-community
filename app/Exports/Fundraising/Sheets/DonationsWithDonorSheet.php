<?php

namespace App\Exports\Fundraising\Sheets;

use App\Models\Fundraising\Donation;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DonationsWithDonorSheet extends DonationsSheet
{
    protected string $currencyColumn = 'H';

    protected string $exchangedCurrencyColumn = 'I';

    public function __construct(Collection $donations, ?int $year = null, private bool $includeAddress = false)
    {
        parent::__construct($donations, $year);
    }

    public function headings(): array
    {
        $cols = [
            __('Donor'),
        ];
        if ($this->includeAddress) {
            $cols[] = __('Address');
        }

        $headings = collect(parent::headings());
        $headings->splice(1, 0, $cols);

        return $headings->toArray();
    }

    /**
     * @param  Donation  $donation
     */
    public function map($donation): array
    {
        $cols = [
            $donation->donor->full_name,
        ];
        if ($this->includeAddress) {
            $cols[] = $donation->donor->fullAddress;
        }

        $map = collect(parent::map($donation));
        $map->splice(1, 0, $cols);

        return $map->toArray();
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);

        if ($this->includeAddress) {
            $sheet->getStyle('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow())->getAlignment()->setVertical('top');
            $sheet->getStyle('C1:C'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        }
    }
}
