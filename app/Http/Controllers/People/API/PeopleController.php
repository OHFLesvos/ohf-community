<?php

namespace App\Http\Controllers\People\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\RegisterCard;
use App\Http\Requests\People\StorePerson;
use App\Http\Requests\People\UpdatePersonDateOfBirth;
use App\Http\Requests\People\UpdatePersonGender;
use App\Http\Requests\People\UpdatePersonNationality;
use App\Http\Requests\People\UpdatePersonPoliceNo;
use App\Http\Requests\People\UpdatePersonRemarks;
use App\Http\Resources\People\Person as PersonResource;
use App\Http\Resources\People\PersonCollection;
use App\Models\People\Person;
use App\Models\People\RevokedCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                Rule::in([
                    'name',
                    'family_name',
                    'date_of_birth',
                    'nationality',
                    'languages',
                    'remarks',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);
        $filter = trim($request->input('filter', ''));

        return new PersonCollection(Person::query()
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('name')
            ->orderBy('family_name')
            ->paginate($pageSize));
    }

    /**
     * Stores a new person
     *
     * @param StorePerson $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePerson $request)
    {
        $this->authorize('create', Person::class);

        $person = new Person();
        $person->fill($request->all());

        if ($request->filled('card_no')) {
            $person->card_no = $request->card_no;
            $person->card_issued = Carbon::now();
        }

        $person->save();

        return response()->json([
            'message' => __('people.person_added'),
            'id' => $person->getRouteKey(),
        ]);
    }

    /**
     * Returns info about a person
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $this->authorize('view', $person);

        return new PersonResource($person);
    }

    /**
     * Updates a person
     *
     * @param StorePerson $request
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(StorePerson $request, Person $person)
    {
        $person->fill($request->all());

        if ($request->filled('card_no')) {
            // TODO check existing code, move to revoked

            $person->card_no = $request->card_no;
            $person->card_issued = Carbon::now();
        }

        $person->save();

        return response()->json([
            'message' => __('people.person_updated'),
        ]);
    }

    /**
     * Returns a list of people according to filter criteria for auto-suggestion fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filterPersons(Request $request)
    {
        // TODO auth check

        $filterQuery = $request->query('query');
        $persons = Person::query()
            ->when($filterQuery, function ($query, $filterQuery) {
                return $query->forFilterTerms(split_by_whitespace($filterQuery));
            })
            ->orderBy('name')
            ->orderBy('family_name')
            ->limit(10)
            ->get()
            ->map(fn (Person $person) => [
                'value' => $person->label,
                'data' => $person->getRouteKey(),
            ]);

        return response()->json([
            'suggestions' => $persons,
        ]);
    }

    /**
     * Update gender of person.
     *
     * @param  \App\Models\People\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonGender  $request
     * @return \Illuminate\Http\Response
     */
    public function updateGender(Person $person, UpdatePersonGender $request)
    {
        $person->gender = $request->gender;
        $person->save();

        return response()->json([
            'gender' => $person->gender,
            'message' => __('people.gender_has_been_registered', [
                'person' => $person->full_name,
            ]),
        ]);
    }

    /**
     * Update date of birth of person.
     *
     * @param  \App\Models\People\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonDateOfBirth  $request
     * @return \Illuminate\Http\Response
     */
    public function updateDateOfBirth(Person $person, UpdatePersonDateOfBirth $request)
    {
        $person->date_of_birth = $request->date_of_birth;
        $person->save();

        return response()->json([
            'date_of_birth' => $person->date_of_birth,
            'age' => $person->age,
            'message' => __('people.date_of_birth_has_been_registered', [
                'person' => $person->full_name,
            ]),
        ]);
    }

    /**
     * Update date of birth of person.
     *
     * @param  \App\Models\People\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonNationality  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNationality(Person $person, UpdatePersonNationality $request)
    {
        $person->nationality = $request->nationality;
        $person->save();

        return response()->json([
            'nationality' => $person->nationality,
            'message' => __('people.nationality_has_been_registered', [
                'person' => $person->full_name,
            ]),
        ]);
    }

    /**
     * Update police number of person.
     *
     * @param  \App\Models\People\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonPoliceNo  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePoliceNo(Person $person, UpdatePersonPoliceNo $request)
    {
        $person->police_no = $request->police_no;
        $person->save();

        return response()->json([
            'police_no' => $person->police_no_formatted,
            'message' => __('people.police_no_has_been_updated', [
                'person' => $person->full_name,
            ]),
        ]);
    }

    /**
     * Update remarks of person.
     *
     * @param  \App\Models\People\Person $person
     * @param  \App\Http\Requests\People\UpdatePersonRemarks  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRemarks(Person $person, UpdatePersonRemarks $request)
    {
        $person->remarks = $request->remarks;
        $person->save();

        return response()->json([
            'remarks' => $person->remarks,
            'message' => __('people.remarks_have_been_updated', [
                'person' => $person->full_name,
            ]),
        ]);
    }

    /**
     * Register code card with person.
     *
     * @param  \App\Http\Requests\People\RegisterCard  $request
     * @return \Illuminate\Http\Response
     */
    public function registerCard(Person $person, RegisterCard $request)
    {

        // Check for revoked card number
        $revoked = RevokedCard::where('card_no', $request->card_no)->first();
        if ($revoked != null) {
            return response()->json([
                'message' => __('people.card_revoked', [ 'card_no' => substr($request->card_no, 0, 7), 'date' => $revoked->created_at ]),
            ], 400);
        }

        // Check for used card number
        if (Person::where('card_no', $request->card_no)->count() > 0) {
            return response()->json([
                'message' => __('people.card_already_in_use', [ 'card_no' => substr($request->card_no, 0, 7) ]),
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
            'message' => __('people.qr_code_card_has_been_registered'),
        ]);
    }
}
