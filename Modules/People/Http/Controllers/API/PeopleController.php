<?php

namespace Modules\People\Http\Controllers\API;

use Modules\People\Entities\Person;
use App\Http\Controllers\Controller;

use Modules\People\Http\Requests\UpdatePersonDateOfBirth;
use Modules\People\Http\Requests\UpdatePersonGender;
use Modules\People\Http\Requests\UpdatePersonNationality;
use Modules\People\Transformers\PersonCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    const filter_fields = [
        'name',
        'family_name',
        'police_no',
        'remarks',
        'nationality',
        'languages',
        'date_of_birth'
    ];

    /**
     * Returns a list of people according to filter criteria.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function filter(Request $request) {
        $condition = [];
        $filter = [];
        foreach (self::filter_fields as $k) {
            if (!empty($request->$k)) {
                $condition[] = [$k, 'LIKE', '%' . $request->$k . '%'];
                $filter[$k] = $request->$k;
            }
        }
        $request->session()->put('people.filter', $filter);

        $q = $persons = Person::where($condition);
        // TODO: validator
        if (isset($request->orderByField) && isset($request->orderByDirection)) {
            $q->orderBy($request->orderByField, $request->orderByDirection);
        }

        // TODO: cyclic module dependency to bank module
        return new PersonCollection($q
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', Config::get('bank.results_per_page'))));
    }

    /**
     * Returns a list of people according to filter criteria for auto-suggestion fields.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filterPersons(Request $request) {
        $qry = Person::limit(10)
            ->orderBy('family_name')
            ->orderBy('name');
        if (isset($request->query()['query'])) {
            $terms = preg_split('/\s+/', $request->query()['query']);
            foreach ($terms as $term) {
                $qry->where(function($wq) use ($term) {
                    $wq->where('search', 'LIKE', '%' . $term  . '%');
                    $wq->orWhere('police_no', $term);
                    $wq->orWhere('case_no_hash', DB::raw("SHA2('". $term ."', 256)"));
                });
            }
        }
        $persons = $qry->get()
            ->map(function($e){ 
                $val = $e->family_name . ' '. $e->name;
                if (!empty($e->date_of_birth)) {
                    $val.= ', ' . $e->date_of_birth . ' (age ' . $e->age . ')';
                }
                if (!empty($e->nationality)) {
                    $val.= ', ' . $e->nationality;
                }
                return [
                    'value' => $val,
                    'data' => $e->getRouteKey(),
                ]; 
            });
        return response()->json(["suggestions" => $persons]);
    }

    /**
     * Update gender of person.
     * 
     * @param  \Modules\People\Entities\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonGender  $request
     * @return \Illuminate\Http\Response
     */
    public function setGender(Person $person, UpdatePersonGender $request) {
        $person->gender = $request->gender;
        $person->save();

        return response()->json([
            'gender' => $person->gender,
            'message' => __('people::people.gender_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }

    /**
     * Update date of birth of person.
     * 
     * @param  \Modules\People\Entities\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonDateOfBirth  $request
     * @return \Illuminate\Http\Response
     */
	public function setDateOfBirth(Person $person, UpdatePersonDateOfBirth $request) {
        $person->date_of_birth = $request->date_of_birth;
        $person->save();

        return response()->json([
            'date_of_birth' => $person->date_of_birth,
            'age' => $person->age,
            'message' => __('people::people.date_of_birth_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }
    
    /**
     * Update date of birth of person.
     * 
     * @param  \Modules\People\Entities\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonNationality  $request
     * @return \Illuminate\Http\Response
     */
	public function setNationality(Person $person, UpdatePersonNationality $request) {
        $person->nationality = $request->nationality;
        $person->save();

        return response()->json([
            'nationality' => $person->nationality,
            'message' => __('people::people.nationality_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }

}
