<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $previous_route = previous_route();
        return [
            'back' => [
                'url' => route($previous_route !== null && $previous_route != 'fundraising.report'
                    ? $previous_route
                    : 'fundraising.donors.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('create', Donor::class),
            ],
        ];
    }

}
