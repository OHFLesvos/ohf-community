<?php

namespace Modules\School\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\School\Entities\SchoolClass;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SchoolClassCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('school.classes.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', SchoolClass::class)
            ]
        ];
    }

}
