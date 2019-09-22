<?php

namespace Modules\School\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\School\Entities\SchoolClass;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SchoolClassEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $class = $view->getData()['class'];
        return [
            'delete' => [
                'url' => route('school.classes.destroy', $class),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $class),
                'confirmation' => __('school::classes.confirm_delete_class')
            ],
            'back' => [
                'url' => route('school.classes.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', SchoolClass::class)
            ]
        ];
    }

}
