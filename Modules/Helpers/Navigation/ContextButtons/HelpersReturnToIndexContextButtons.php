<?php

namespace Modules\Helpers\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Helpers\Entities\Helper;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HelpersReturnToIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.helpers.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Helper::class)
            ]
        ];
    }

}
