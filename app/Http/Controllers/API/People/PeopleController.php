<?php

namespace App\Http\Controllers\API\People;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePersonDateOfBirth;
use App\Http\Requests\UpdatePersonGender;
use Carbon\Carbon;
use App\Person;

class PeopleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateGender(UpdatePersonGender $request) {
        $person = Person::find($request->person_id);
        if ($person != null) {
            $person->gender = $request->gender;
            $person->save();
            return response()->json([
                'gender' => $person->gender,
            ]);
        }
    }

	public function updateDateOfBirth(UpdatePersonDateOfBirth $request) {
        $person = Person::find($request->person_id);
        if ($person != null) {
            if (isset($request->date_of_birth) && (new Carbon($request->date_of_birth))->lte(Carbon::today())) {
                $person->date_of_birth = $request->date_of_birth;
                $person->save();
                return response()->json([
                    'date_of_birth' => $person->date_of_birth,
                    'age' => $person->age,
                ]);
            } else {
                return response()->json(["Invalid or empty date!"], 400);
            }
        }
    }
}
