<?php

namespace Modules\School\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\School\Entities\SchoolClass;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SchoolClassIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('school.classes.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', SchoolClass::class)
            ],
            // 'export' => [
            //     'url' => route('school.classes.export'),
            //     'caption' => __('app.export'),
            //     'icon' => 'download',
            //     'authorized' => Auth::user()->can('list', SchoolClass::class)
            // ]
        ];
    }

}
