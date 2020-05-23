<?php

namespace App\Navigation\ContextButtons\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\View\View;

class DonorCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('fundraising.donors.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => request()->user()->can('create', Donor::class),
            ],
        ];
    }

}
