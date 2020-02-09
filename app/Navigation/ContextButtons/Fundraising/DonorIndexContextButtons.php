<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Fundraising\Donor;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonorIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('fundraising.donors.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Donor::class)
            ],
            'export' => [
                'url' => route('fundraising.donors.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Donor::class)
            ]
        ];
    }

}
