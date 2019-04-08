<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Fundraising\Entities\Donor;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonorCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('fundraising.donors.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('create', Donor::class)
            ]
        ];
    }

}
