<?php

namespace Modules\School\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\School\Entities\Student;
use Modules\School\Entities\SchoolClass;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SchoolClassStudentsShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $class = $view->getData()['class'];
        $student = $view->getData()['student'];
        return [
            'delete' => [
                'url' => route('school.classes.students.destroy', [$class, $student]),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => true, // TODO Auth::user()->can('delete', $student),
                'confirmation' => __('school::students.confirm_delete_student_from_class')
            ],
            'back' => [
                'url' => route('school.classes.students.index', $class),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Student::class)
            ]
        ];
    }

}
