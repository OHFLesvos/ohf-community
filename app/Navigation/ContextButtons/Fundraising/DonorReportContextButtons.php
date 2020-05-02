<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonorReportContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('fundraising.donors.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('create', Donor::class),
            ],
        ];
    }

}
