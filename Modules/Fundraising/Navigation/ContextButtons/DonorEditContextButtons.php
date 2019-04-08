<?php

namespace Modules\Fundraising\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DonorEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $donor = $view->getData()['donor'];
        return [
            'back' => [
                'url' => route('fundraising.donors.show', $donor),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $donor)
            ]
        ];
    }

}
