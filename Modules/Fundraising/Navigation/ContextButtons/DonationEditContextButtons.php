<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonationEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $donor = $view->getData()['donor'];
        $donation = $view->getData()['donation'];
        return [
            'delete' => [
                'url' => route('fundraising.donations.destroy', [$donor, $donation]),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $donation),
                'confirmation' => __('fundraising::fundraising.confirm_delete_donation')
            ],
            'back' => [
                'url' => route('fundraising.donors.show', $donor),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $donor)
            ]
        ];

    }

}
