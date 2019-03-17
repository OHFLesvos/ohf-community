<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Fundraising\Entities\Donation;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonationImportContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('fundraising.donations.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Donation::class)
            ]
        ];
    }

}
