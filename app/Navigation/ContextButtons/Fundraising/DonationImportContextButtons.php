<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donation;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\View\View;

class DonationImportContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('fundraising.donations.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => request()->user()->can('viewAny', Donation::class),
            ],
        ];
    }

}
