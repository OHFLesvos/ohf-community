<?php

namespace App\Exports\Fundraising\Sheets;

use App\Models\Fundraising\Donation;

class DonationsWithDonorSheet extends DonationsSheet
{
    protected string $currencyColumn = 'H';

    protected string $exchangedCurrencyColumn = 'I';

    public function headings(): array
    {
        $headings = collect(parent::headings());
        $headings->splice(1, 0, [
            __('Donor'),
        ]);

        return $headings->toArray();
    }

    /**
     * @param  Donation  $donation
     */
    public function map($donation): array
    {
        $map = collect(parent::map($donation));
        $map->splice(1, 0, [
            $donation->donor->full_name,
        ]);

        return $map->toArray();
    }
}
