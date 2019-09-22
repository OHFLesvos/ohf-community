<?php

namespace Modules\School\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\School\Entities\SchoolClass;
use Modules\School\Entities\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        // TODO
    }
}
