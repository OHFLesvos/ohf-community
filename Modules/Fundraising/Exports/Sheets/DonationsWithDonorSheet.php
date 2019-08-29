<?php

namespace Modules\Fundraising\Exports\Sheets;

use Modules\Fundraising\Entities\Donation;

class DonationsWithDonorSheet extends DonationsSheet
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return array_merge(
            parent::headings(),
            [
                __('app.first_name'),
                __('app.last_name'),
                __('app.company'),
                __('app.city'),
                __('app.country'),
                __('app.email'),
            ]
        );
    }

    /**
    * @var Donation $donation
    */
    public function map($donation): array
    {
        return array_merge(
            parent::map($donation),
            [
                $donation->donor->first_name,
                $donation->donor->last_name,
                $donation->donor->company,
                $donation->donor->city,
                $donation->donor->country_name,
                $donation->donor->email,
            ]            
        );
    }

}
