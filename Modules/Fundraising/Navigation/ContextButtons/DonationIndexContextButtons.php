<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Fundraising\Entities\Donation;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonationIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'import' => [
                'url' => route('fundraising.donations.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Donation::class)
            ],
            'export' => [
                'url' => route('fundraising.donations.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Donation::class)
            ],
        ];
    }

}
