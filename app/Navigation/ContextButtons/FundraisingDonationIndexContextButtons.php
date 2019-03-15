<?php

namespace App\Navigation\ContextButtons;

use App\Donation;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class FundraisingDonationIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'import' => [
                'url' => route('fundraising.donations.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Donation::class)
            ]
        ];
    }

}
