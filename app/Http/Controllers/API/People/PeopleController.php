<?php

namespace App\Http\Controllers\API\People;

use App\Person;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\UpdatePersonDateOfBirth;
use App\Http\Requests\People\UpdatePersonGender;
use App\Http\Requests\People\UpdatePersonNationality;

class PeopleController extends Controller
{
    /**
     * Update gender of person.
     * 
     * @param  \App\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonGender  $request
     * @return \Illuminate\Http\Response
     */
    public function setGender(Person $person, UpdatePersonGender $request) {
        $this->authorize('update', $person);

        $person->gender = $request->gender;
        $person->save();

        return response()->json([
            'gender' => $person->gender,
            'message' => __('people.gender_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }

    /**
     * Update date of birth of person.
     * 
     * @param  \App\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonDateOfBirth  $request
     * @return \Illuminate\Http\Response
     */
	public function setDateOfBirth(Person $person, UpdatePersonDateOfBirth $request) {
        $this->authorize('update', $person);

        $person->date_of_birth = $request->date_of_birth;
        $person->save();

        return response()->json([
            'date_of_birth' => $person->date_of_birth,
            'age' => $person->age,
            'message' => __('people.date_of_birth_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }
    
    /**
     * Update date of birth of person.
     * 
     * @param  \App\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonNationality  $request
     * @return \Illuminate\Http\Response
     */
	public function setNationality(Person $person, UpdatePersonNationality $request) {
        $this->authorize('update', $person);

        $person->nationality = $request->nationality;
        $person->save();

        return response()->json([
            'nationality' => $person->nationality,
            'message' => __('people.nationality_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }

}
