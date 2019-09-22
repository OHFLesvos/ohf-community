<?php

namespace Modules\School\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\School\Entities\Student;
use Modules\School\Entities\SchoolClass;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SchoolClassStudentsIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $class = $view->getData()['class'];
        return [
            'export' => [
                'url' => route('school.classes.students.export', $class),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Student::class)
            ],       
            'back' => [
                'url' => route('school.classes.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', SchoolClass::class)
            ]
        ];
    }

}
