<?php

namespace Modules\Fundraising\Exports\Sheets;

use Modules\Fundraising\Entities\Donation;

class DonationsWithDonorSheet extends DonationsSheet
{
    protected $currencyColumn = 'H';
    protected $exchangedCurrencyColumn = 'I';

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = collect(parent::headings());
        $headings->splice(1, 0, [
                __('fundraising::fundraising.donor')
            ]);
        return $headings ->toArray();
    }

    /**
    * @var Donation $donation
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
