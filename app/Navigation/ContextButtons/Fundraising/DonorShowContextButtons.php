<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class DonorShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $previous_route = previous_route();
        $donor = $view->getData()['donor'];
        return [
            'action' => [
                'url' => route('fundraising.donors.edit', $donor),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => request()->user()->can('update', $donor),
            ],
            'export' => [
                'url' => route('api.fundraising.donors.donations.export', $donor),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => request()->user()->can('viewAny', Donation::class),
            ],
            'vcard' => [
                'url' => route('api.fundraising.donors.vcard', $donor),
                'caption' => __('app.vcard'),
                'icon' => 'address-card',
                'authorized' => request()->user()->can('view', $donor),
            ],
            'delete' => [
                'url' => route('fundraising.donors.destroy', $donor),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' =>request()->user()->can('delete', $donor),
                'confirmation' => __('fundraising.confirm_delete_donor'),
            ],
            'back' => [
                'url' => route($previous_route !== null && $previous_route != Route::currentRouteName()
                    ? $previous_route
                    : 'fundraising.donors.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => request()->user()->can('viewAny', Donor::class),
            ],
        ];
    }

}
