<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DonorIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('fundraising.donors.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => request()->user()->can('create', Donor::class),
            ],
            'report' => [
                'url' => route('fundraising.report'),
                'caption' => __('app.report'),
                'icon' => 'chart-pie',
                'authorized' => Gate::allows('view-fundraising-reports'),
            ],
            'export' => [
                'url' => route('api.fundraising.donors.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => request()->user()->can('viewAny', Donor::class),
            ],
        ];
    }

}
