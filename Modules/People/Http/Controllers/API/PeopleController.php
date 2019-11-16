<?php

namespace Modules\People\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;
use Modules\People\Entities\RevokedCard;
use Modules\People\Http\Requests\UpdatePersonDateOfBirth;
use Modules\People\Http\Requests\UpdatePersonGender;
use Modules\People\Http\Requests\UpdatePersonNationality;
use Modules\People\Http\Requests\RegisterCard;
use Modules\People\Transformers\PersonCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PeopleController extends Controller
{
    /**
     * Returns a list of people according to filter criteria.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                'in:name,family_name,date_of_birth,nationality,languages,remarks'
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc'
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);
        $filter = trim($request->input('filter', ''));
            
        return new PersonCollection(self::createQuery($filter)
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('name')
            ->orderBy('family_name')
            ->paginate($pageSize));   
    }

    private static function createQuery(String $filter)
    {
        $query = Person::query();
        if (!empty($filter)) {
            self::applyFilter($query, $filter);
        }
        return $query;
    }

    private static function applyFilter(&$query, $filter)
    {
        $query->where(function($wq) use($filter) {
            return $wq->where(DB::raw('CONCAT(name, \' \', family_name)'), 'LIKE', '%' . $filter . '%')
                ->orWhere(DB::raw('CONCAT(family_name, \' \', name)'), 'LIKE', '%' . $filter . '%')
                ->orWhere('name', 'LIKE', '%' . $filter . '%')
                ->orWhere('family_name', 'LIKE', '%' . $filter . '%')
                ->orWhere('date_of_birth', $filter)
                ->orWhere('nationality', 'LIKE', '%' . $filter . '%')
                ->orWhere('police_no', $filter)
                ->orWhere('remarks', 'LIKE', '%' . $filter . '%');
        });
    }

    /**
     * Returns a list of people according to filter criteria for auto-suggestion fields.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filterPersons(Request $request)
    {
        $qry = Person::limit(10)
            ->orderBy('name')
            ->orderBy('family_name');
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
     * @param  \Modules\People\Http\Requests\UpdatePersonGender  $request
     * @return \Illuminate\Http\Response
     */
    public function setGender(Person $person, UpdatePersonGender $request)
    {
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
     * @param  \Modules\People\Http\Requests\UpdatePersonDateOfBirth  $request
     * @return \Illuminate\Http\Response
     */
    public function setDateOfBirth(Person $person, UpdatePersonDateOfBirth $request)
    {
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
     * @param  \Modules\People\Http\Requests\UpdatePersonNationality  $request
     * @return \Illuminate\Http\Response
     */
    public function setNationality(Person $person, UpdatePersonNationality $request)
    {
        $person->nationality = $request->nationality;
        $person->save();

        return response()->json([
            'nationality' => $person->nationality,
            'message' => __('people::people.nationality_has_been_registered', [
                'person' => $person->family_name . ' ' . $person->name,
            ]),
        ]);
    }

    /**
     * Register code card with person.
     * 
     * @param  \Modules\People\Http\Requests\RegisterCard  $request
     * @return \Illuminate\Http\Response
     */
    public function registerCard(Person $person, RegisterCard $request)
    {
        
        // Check for revoked card number
        $revoked = RevokedCard::where('card_no', $request->card_no)->first();
        if ($revoked != null) {
            return response()->json([
                'message' => __('people::people.card_revoked', [ 'card_no' => substr($request->card_no, 0, 7), 'date' => $revoked->created_at ]),
            ], 400);
        }

        // Check for used card number
        if (Person::where('card_no', $request->card_no)->count() > 0) {
            return response()->json([
                'message' => __('people::people.card_already_in_use', [ 'card_no' => substr($request->card_no, 0, 7) ]),
            ], 400);
        }

        // If person already has a card number, revoke it
        if ($person->card_no != null) {
            $revoked = new RevokedCard();
            $revoked->card_no = $person->card_no;
            $person->revokedCards()->save($revoked);
        }

        // Issue new card
        $person->card_no = $request->card_no;
        $person->card_issued = Carbon::now();
        $person->save();
        return response()->json([
            'message' => __('people::people.qr_code_card_has_been_registered'),
        ]);
    }
}
