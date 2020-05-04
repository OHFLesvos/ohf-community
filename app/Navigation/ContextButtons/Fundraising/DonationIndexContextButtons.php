<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donation;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DonationIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'report' => [
                'url' => route('fundraising.report'),
                'caption' => __('app.report'),
                'icon' => 'chart-pie',
                'authorized' => Gate::allows('view-fundraising-reports'),
            ],
            'import' => [
                'url' => route('fundraising.donations.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Donation::class),
            ],
            'export' => [
                'url' => route('fundraising.donations.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Donation::class),
            ],
        ];
    }

}
