<?php

namespace Modules\School\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\School\Entities\SchoolClass;
use Modules\School\Entities\Student;
use Modules\School\Exports\StudentsExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class SchoolClassStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(SchoolClass $class)
    {
        $this->authorize('list', Student::class);

        return view('school::classes.students.index', [
            'class' => $class,
        ]);
    }

    public function export(SchoolClass $class)
    {
        $this->authorize('list', Student::class);

        $file_name = Config::get('app.name') . ' - ' . __('school::school.school') . ' - ' . $class->name .' (' . $class->start_date->toDateString() .' - '. $class->end_date->toDateString() . ')';

        return (new StudentsExport($class->students()))->download($file_name . '.' . 'xlsx');
    }
}
