<?php

namespace Modules\People\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadSpreadsheet;

use Modules\People\Entities\Person;
use Modules\People\Exports\PeopleExport;
use Modules\People\Imports\PeopleImport;
use Modules\People\Http\Requests\StorePerson;

use Modules\Bank\Entities\CouponHandout;    // TODO: fix circular dependency

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Validator;

use Carbon\Carbon;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;

use Countries;

class PeopleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class);
    }

    function index(Request $request)
    {
    	return view('people::index');
    }

    public function create()
    {
        $countries = Countries::getList('en');
        return view('people::create', [
            'countries' => $countries,
        ]);
    }

    public function store(StorePerson $request)
    {
        $person = new Person();
        $person->fill($request->all());
		$person->save();

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
                    $child->nationality = !empty($request->nationality) ? $request->nationality : null;
                    if ($person->gender == 'f') {
                        $child->mother()->associate($person);
                    } else if ($person->gender == 'm') {
                        $child->father()->associate($person);
                    }
                    $child->save();
                }
            }
        }

		return redirect()->route('people.index')
				->with('success', __('people::people.person_added'));		
	}

    public function show(Person $person)
    {
        return view('people::show', [
            'person' => $person,
        ]);
    }

    public function qrCode(Person $person)
    {
        $qrCode = new QrCode($person->public_id);
        $qrCode->setLabel($person->fullName, 16, null, LabelAlignment::CENTER);   
        return response($qrCode->writeString())
            ->header('Content-Type', $qrCode->getContentType());
    }

    public function edit(Person $person)
    {
        return view('people::edit', [
            'person' => $person,
            'countries' => Countries::getList('en')
		]);
	}

    public function update(StorePerson $request, Person $person)
    {
        $person->fill($request->all());
        $person->save();
        return redirect()->route('people.show', $person)
                ->with('success', __('people::people.person_updated'));
	}

    public function relations(Person $person)
    {
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
        return view('people::relations', [
            'person' => $person,
            'types' => $types,
		]);
    }

    public function addRelation(Person $person, Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|in:father,mother,partner,child',
            'relative' => [
                'required',
                Rule::exists('persons', $person->getRouteKeyName())
                    ->whereNot($person->getRouteKeyName(), $person->getRouteKey())
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

        $relative = Person::where('public_id', $request->relative)->first();
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

    public function removeChild(Person $person, Person $child)
    {
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

    public function removePartner(Person $person)
    {
        $partner = $person->partner;
        $partner->partner_id = null;
        $partner->save();
        $person->partner_id = null;
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Partner "' . $partner->family_name . ' ' . $partner->name . '" has been removed!');
    }

    public function removeFather(Person $person)
    {
        $father = $person->father;
        $person->father()->dissociate();
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Father "' . $father->family_name . ' ' . $father->name . '" has been removed!');
    }

    public function removeMother(Person $person)
    {
        $mother = $person->mother;
        $person->mother()->dissociate();
        $person->save();
        return redirect()->route('people.relations', $person)
            ->with('success', 'Mother "' . $mother->family_name . ' ' . $mother->name . '" has been removed!');
    }
    
    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('people.index')
            ->with('success', __('people::people.person_deleted'));
    }

    public function duplicates()
    {
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

        return view('people::duplicates', [
            'duplicates' => $duplicates,
            'total' => Person::count(),
            'actions' => [
                'nothing'=> 'Do nothing',
                'merge'=>'Merge',
            ],
		]);
    }
    
    public function applyDuplicates(Request $request)
    {
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

    private static function mergePersons($ids)
    {
        // Get master and related persons
        $persons = Person::whereIn('public_id', $ids)
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

    private static function getFirstNonEmptyAttributeFromCollection($collection, $attributeName)
    {
        return $collection->pluck($attributeName)
            ->filter(function($e) {
                return $e != null;
            })
            ->first();
    }
  
    public function bulkAction(Request $request)
    {
        Validator::make($request->all(), [
            'selected_action' => 'required|in:delete,merge',
            'selected_people' => 'array',
        ])->validate();
        $action = $request->selected_action;
        $ids = $request->selected_people;

        // Bulk delete
        if ($action == 'delete') {

            $n = Person::whereIn('public_id', $ids)->delete();

            return redirect()->route('people.index')
                ->with('success', trans_choice('people::people.deleted_n_persons', $n, [ 'num' => $n ]));
        }

        // Merge
        if ($action == 'merge') {
            
            $n = self::mergePersons($ids);

            return redirect()->route('people.index')
                ->with('success', __('people::people.merged_n_persons', [ 'num' => $n ]));
        }
    }

    public function export()
    {
        $file_name = __('people::people.people') . ' ' . Carbon::now()->toDateString();
        return (new PeopleExport)->download($file_name . '.' . 'xlsx');
    }

    function import()
    {
        return view('people::import');
    }

    function doImport(UploadSpreadsheet $request)
    {
        $import = new PeopleImport();
        $import->import($request->file('file'));

        return redirect()->route('people.index')
				->with('success', __('app.imported_num_records', ['num' => $import->count()]));
    }

}
