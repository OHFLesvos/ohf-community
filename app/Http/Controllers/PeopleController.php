<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\CouponHandout;
use App\Http\Requests\StorePerson;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PeopleController extends ParentController
{
    const filter_fields = [
        'name',
        'family_name',
        'police_no',
        'registration_no',
        'section_card_no',
        'remarks',
        'nationality',
        'languages',
        'date_of_birth'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Person::class);

    }

    function index(Request $request) {
        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'people.index']);

		return view('people.index', [
            'filter' => session('people.filter', [])
        ]);
    }

    public function create() {
        $usedInBank = preg_match('/^bank./', session('peopleOverviewRouteName', 'people.index'));
        $countries = \Countries::getList('en');
        if ($usedInBank) {
            return view('people.create_in_bank', [
                'countries' => $countries,
            ]);
        }
        return view('people.create', [
            'countries' => $countries,
        ]);
    }

	public function store(StorePerson $request) {
        $person = new Person();
		$person->name = $request->name;
        $person->family_name = $request->family_name;
        $person->gender = $request->gender;
		$person->date_of_birth = $request->date_of_birth;
		$person->police_no = !empty($request->police_no) ? $request->police_no : null;
		$person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
		$person->languages = !empty($request->languages) ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $request->languages) : null;
        $person->card_no = $request->card_no;
		$person->save();

        $redirectFilter[] = $person->search;

        if (isset($request->child_family_name) && is_array($request->child_family_name)) {
            for ($i = 0; $i < count($request->child_family_name); $i++) {
                if (!empty($request->child_family_name[$i]) && !empty($request->child_name[$i]) && !empty($request->child_gender[$i])) {
                    $child = new Person();
                    $child->name = $request->child_name[$i];
                    $child->family_name = $request->child_family_name[$i];
                    $child->gender = $request->child_gender[$i];
                    $child->date_of_birth = $request->child_date_of_birth[$i];

                    $child->police_no = !empty($request->police_no) ? $request->police_no : null;
                    $child->case_no = !empty($request->case_no) ? $request->case_no : null;
                    $child->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
                    $child->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
                    $child->nationality = !empty($request->nationality) ? $request->nationality : null;
                    if ($person->gender == 'f') {
                        $child->mother()->associate($person);
                    } else if ($person->gender == 'm') {
                        $child->father()->associate($person);
                    }
                    $child->save();
                    $redirectFilter[] = $child->search;
                }
            }
        }

        $isBank = preg_match('/^bank./', session('peopleOverviewRouteName', 'people.index'));
		if ( $isBank ) {
            $request->session()->put('filter', implode(' OR ', $redirectFilter));
            return redirect()->route('bank.withdrawalSearch')
                ->with('success', 'Person has been added!');
        }

		return redirect()->route(session('peopleOverviewRouteName', 'people.index'))
				->with('success', 'Person has been added!');		
	}

    public function show(Person $person) {
        return view('people.show', [
            'person' => $person,
        ]);
    }

    public function qrCode(Person $person) {
        $qrCode = new QrCode($person->public_id);
        $qrCode->setLabel($person->family_name . ' ' . $person->name, 16, null, LabelAlignment::CENTER);   
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

    public function edit(Person $person) {
        return view('people.edit', [
            'person' => $person,
            'countries' => \Countries::getList('en')
		]);
	}

	public function update(StorePerson $request, Person $person) {
        $person->name = $request->name;
        $person->family_name = $request->family_name;
        $person->gender = $request->gender;
        $person->date_of_birth = $request->date_of_birth;
        $person->police_no = !empty($request->police_no) ? $request->police_no : null;
        $person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
        $person->languages = !empty($request->languages) ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $request->languages) : null;
        $person->save();
        return redirect()->route('people.show', $person)
                ->with('success', 'Person has been updated!');
	}

    public function relations(Person $person) {
        $types = [];
        if ($person->father == null) {
            $types['father'] = 'Father';
        }
        if ($person->mother == null) {
            $types['mother'] = 'Mother';
        }
        if ($person->partner == null) {
            $types['partner'] = 'Partner';
        }
        if ($person->gender != null) {
            $types['child'] = 'Child';
        }
        return view('people.relations', [
            'person' => $person,
            'types' => $types,
		]);
    }

    public function filterPersons(Request $request) {
        $qry = Person::limit(10)
            ->orderBy('family_name')
            ->orderBy('name');
        if (isset($request->query()['query'])) {
            $qry->where('search', 'LIKE', '%' . $request->query()['query'] . '%');
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
                    'data' => $e->id,
                ]; 
            });
        return response()->json(["suggestions" => $persons]);
    }

    public function addRelation(Person $person, Request $request) {
        Validator::make($request->all(), [
            'type' => 'required|in:father,mother,partner,child',
            'relative' => [
                'required',
                Rule::exists('persons', 'id')
                    ->whereNot('id', $person->id)
                    ->whereNull(function ($query) use ($request) {
                        if ($request->type == 'father') {
                            $query->whereNull('father_id');
                        }
                        if ($request->type == 'mother') {
                            $query->whereNull('mother_id');
                        }                        
                        if ($request->type == 'partner') {
                            $query->whereNull('partner_id');
                        }                        
                    }),
            ],
        ])->validate();

        $relative = Person::find($request->relative);
        $label = '';
        if ($request->type == 'father') {
            $person->father()->associate($relative);
            $label = 'Father';
        }
        else if ($request->type == 'mother') {
            $person->mother()->associate($relative);
            $label = 'Mother';
        }
        else if ($request->type == 'partner') {
            $relative->partner_id = $person->id;
            $relative->save();
            $person->partner_id = $relative->id;
            $label = 'Partner';
        }
        else if ($request->type == 'child') {
            if ($person->gender == 'f') {
                $relative->mother()->associate($person);
            } else if ($person->gender == 'm') {
                $relative->father()->associate($person);
            }
            $relative->save();
            $label = 'Child';
        }
        $person->save();

        return redirect()->route('people.relations', $person)
            ->with('success', $label . ' "' . $relative->family_name . ' ' . $relative->name . '" has been added!');
    }

    public function removeChild(Person $person, Person $child) {
        if ($person->gender == 'm') {
            $child->father()->dissociate();
        }
        else if ($person->gender == 'f') {
            $child->mother()->dissociate();
        }
        $child->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Child "' . $child->family_name . ' ' . $child->name . '" has been removed!');
    }

    public function removePartner(Person $person) {
        $partner = $person->partner;
        $partner->partner_id = null;
        $partner->save();
        $person->partner_id = null;
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Partner "' . $partner->family_name . ' ' . $partner->name . '" has been removed!');
    }

    public function removeFather(Person $person) {
        $father = $person->father;
        $person->father()->dissociate();
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Father "' . $father->family_name . ' ' . $father->name . '" has been removed!');
    }

    public function removeMother(Person $person) {
        $mother = $person->mother;
        $person->mother()->dissociate();
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Mother "' . $mother->family_name . ' ' . $mother->name . '" has been removed!');
    }
    
    public function destroy(Person $person) {
        $person->delete();

        return redirect()->route(session('peopleOverviewRouteName', 'people.index'))
            ->with('success', 'Person has been deleted!');
    }

    public function duplicates() {
        $names = [];
        Person::orderBy('family_name')
            ->orderBy('name')
            ->with(['father', 'mother', 'partner'])
            ->get()
            ->each(function($e) use (&$names) {
                $names[$e->family_name. ' ' . $e->name][$e->id] = $e;
            });
        $duplicates = collect($names)
            ->filter(function($e){
                return count($e) > 1;
            });

        return view('people.duplicates', [
            'duplicates' => $duplicates,
            'total' => Person::count(),
            'actions' => [
                'nothing'=> 'Do nothing',
                'merge'=>'Merge',
            ],
		]);
    }
    
    public function applyDuplicates(Request $request) {
        Validator::make($request->all(), [
            'action' => 'required|array',
        ])->validate();
        $merged = 0;
        foreach($request->action as $idsString => $action) {
            if ($action == 'merge') {
                $ids = explode(',', $idsString);
                self::mergePersons($ids);
                $merged++;
            }
        }

        return redirect()->route('people.index')
            ->with('success', 'Done (merged ' . $merged . ' persons).');
    }

    private static function mergePersons($ids) {

        // Get master and related persons
        $persons = Person::whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->get();
        $master = $persons->shift();

        // Merge basic attributes
        foreach ([
                'gender',
                'date_of_birth',
                'nationality',
                'languages',
                'police_no',
                'case_no_hash',
                'registration_no',
                'section_card_no',
                'card_no',
                'card_issued'
            ] as $attr) {
            if ($master->$attr == null) {
                $master->$attr = self::getFirstNonEmptyAttributeFromCollection($persons, $attr);
            }
        }

        // Merge mother
        if ($master->mother == null) {
            $mother = $persons->filter(function($e) {
                    return $e->mother != null;
                })
                ->pluck('mother')
                ->first();
            if ($mother != null) {
                $master->mother()->dissociate();
                $master->mother()->associate($mother);
            }
        }
        // Merge father
        if ($master->father == null) {
            $father = $persons->filter(function($e) {
                    return $e->father != null;
                })
                ->pluck('father')
                ->first();
            if ($father != null) {
                $master->father()->dissociate();
                $master->father()->associate($father);
            }
        }

        // Merge children
        $persons->each(function($e) use($master) {
            $e->children()->each(function($child) use($master) {
                if ($master->gender == 'f') {
                    $child->mother()->dissociate();
                    $child->mother()->associate($master);
                }
                else if ($master->gender == 'm') {
                    $child->father()->dissociate();
                    $child->father()->associate($master);
                }
                $child->save();
            });
        });

        // TODO partner merge

        // Merge coupon handouts
        CouponHandout::whereIn('person_id', $persons->pluck('id')->toArray())
            ->get()
            ->each(function($e) use($master) {
                $e->person_id = $master->id;
                try {
                    $e->save();
                } catch(\Illuminate\Database\QueryException $ex){ 
                    // Ignore
                    Log::notice('Skip adapting coupon handout during merge: ' . $ex->getMessage());
                }
            });

        // Merge remarks
        $remarks = $persons->pluck('remarks')
            ->push($master->remarks)
            ->filter(function($e) {
                return $e != null;
            })
            ->unique()
            ->implode("\n");
        if (!empty($remarks)) {
            $master->remarks = $remarks;
        }

        // Save master, remove duplicates
        $master->save();
        $persons->each(function($e) {
            $e->forceDelete();
        });

        return count($ids);
    }

    private static function getFirstNonEmptyAttributeFromCollection($collection, $attributeName) {
        return $collection->pluck($attributeName)
            ->filter(function($e) {
                return $e != null;
            })
            ->first();
    }

	public function filter(Request $request) {
        $this->authorize('list', Person::class);

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
        return $q
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', Config::get('bank.results_per_page')));
    }
    
    public function bulkAction(Request $request) {
        $this->authorize('manage-people');

        Validator::make($request->all(), [
            'selected_action' => 'required|in:delete,merge',
            'selected_people' => 'array',
        ])->validate();
        $action = $request->selected_action;
        $ids = $request->selected_people;

        // Bulk delete
        if ($action == 'delete') {

            $n = Person::destroy($ids);

            return redirect()->route('people.index')
                ->with('success', trans_choice('people.deleted_n_persons', $n, [ 'num' => $n ]));
        }

        // Merge
        if ($action == 'merge') {
            
            $n = self::mergePersons($ids);

            return redirect()->route('people.index')
                ->with('success', __('people.merged_n_persons', [ 'num' => $n ]));
        }
    }

    public function export() {
        $this->authorize('export', Person::class);

        \Excel::create('People_' . Carbon::now()->toDateString(), function($excel) {
            $excel->sheet(__('people.people'), function($sheet) {
                $persons = Person::orderBy('name', 'asc')
                    ->orderBy('family_name', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('people.export',[
                    'persons' => $persons
                ]);
            });
        })->export('xlsx');
    }

    function import() {
        $this->authorize('create', Person::class);

        return view('people.import');
    }

    function doImport(Request $request) {
        $this->authorize('create', Person::class);

        $this->validate($request, [
            'file' => 'required|file',
        ]);
        $file = $request->file('file');
        
        \Excel::selectSheets()->load($file, function($reader) {
            
            /** TODO */
            DB::table('transactions')->delete();
            DB::table('persons')->delete();

            $reader->each(function($sheet) {
            
                // Loop through all rows
                $sheet->each(function($row) {
                    
                    if (!empty($row->name)) {
                        $person = Person::create([
                            'name' => $row->name,
                            'family_name' => isset($row->surname) ? $row->surname : $row->family_name,
                            'police_no' => is_numeric($row->police_no) ? $row->police_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
                            'nationality' => $row->nationality,
                            'languages' => !empty($row->languages) ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $row->languages) : null,
                            'remarks' => $row->remarks,
                        ]);
                    }
                });

            });
        });
		return redirect()->route('people.index')
				->with('success', _('app.import_successful'));
    }

}
