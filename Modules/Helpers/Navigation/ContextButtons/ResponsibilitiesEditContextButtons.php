<?php

namespace Modules\Helpers\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Helpers\Entities\Responsibility;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ResponsibilitiesEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $responsibility = $view->getData()['responsibility'];
        return [
            'delete' => [
                'url' => route('people.helpers.responsibilities.destroy', $responsibility),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $responsibility),
                'confirmation' => __('helpers::responsibilities.confirm_delete_responsibility')
            ],            
            'back' => [
                'url' => route('people.helpers.responsibilities.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Responsibility::class)
            ]
        ];
    }

}
