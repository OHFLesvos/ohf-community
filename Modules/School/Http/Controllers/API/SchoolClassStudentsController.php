<?php

namespace Modules\School\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;
use Modules\School\Entities\SchoolClass;
use Modules\School\Entities\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchoolClassStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addStudentToClass(SchoolClass $class, Request $request)
    {
        $this->authorize('create', Student::class);
        
        $person = Person::where('public_id', $request->person)->firstOrFail();
        $class->students()->save($person);
        
        return response()->json([], 201);
    }
}
