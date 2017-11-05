<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StorePerson;
use App\Http\Requests\StoreTransaction;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{
    const DEFAULT_RESULTS_PER_PAGE = 15;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index() {
        $this->authorize('list', Person::class);

        session(['peopleOverviewRouteName' => 'people.index']);

		return view('people.index', [
		    'buttons' => [
                'action' => [
                    'url' => route('people.create'),
                    'caption' => 'Register',
                    'icon' => 'plus-circle',
                    'icon_floating' => 'plus',
                    'authorized' => Auth::user()->can('create', Person::class)
                ]
            ],
		    'menu' => [
                [
                    'url' => route('people.charts'),
                    'caption' => 'Charts',
                    'icon' => 'line-chart',
                    'authorized' => true
                ],
                [
                    'url' => route('people.export'),
                    'caption' => 'Export',
                    'icon' => 'download',
                    'authorized' => Auth::user()->can('list', Person::class)
                ],
                [
                    'url' => route('people.import'),
                    'caption' => 'Import',
                    'icon' => 'upload',
                    'authorized' => Auth::user()->can('create', Person::class)
                ],
            ]
		]);
    }

    private function getPeopleOverviewRouteName() {
        return session('peopleOverviewRouteName', 'people.index');
    }

    public function create() {
        $this->authorize('create', Person::class);

        return view('people.create', [
            'allow_transaction' => $this->getPeopleOverviewRouteName() == 'bank.index',
            'transaction_value' => \Setting::get('bank.transaction_default_value', BankController::TRANSACTION_DEFAULT_VALUE),
            'buttons' => [
                'back' => [
                    'url' => route($this->getPeopleOverviewRouteName()),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', Person::class)
                ]
            ]
		]);
    }

	public function store(StorePerson $request) {
        $this->authorize('create', Person::class);

        $person = new Person();
		$person->name = $request->name;
		$person->family_name = $request->family_name;
		$person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
		$person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->medical_no = !empty($request->medical_no) ? $request->medical_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
		$person->nationality = !empty($request->nationality) ? $request->nationality : null;
		$person->languages = !empty($request->languages) ? $request->languages : null;
		$person->skills = !empty($request->skills) ? $request->skills : null;
		$person->save();

		if ( $this->getPeopleOverviewRouteName() == 'bank.index' ) {
            if (!empty($request->value)) {
                $transaction = new Transaction();
                $transaction->person_id = $person->id;
                $transaction->value = $request->value;
                $transaction->save();
            }
            $request->session()->flash('filter', $person->name . ' ' . $person->family_name);
        }

		return redirect()->route($this->getPeopleOverviewRouteName())
				->with('success', 'Person has been added!');		
	}

    public function show(Person $person) {
        $this->authorize('view', $person);

        return view('people.show', [
            'person' => $person,
            'transactions' => $person->transactions()
                ->select('created_at', 'value')
                ->orderBy('created_at', 'desc')
                ->paginate(),
            'buttons' => [
                'action' => [
                    'url' => route('people.edit', $person),
                    'caption' => 'Edit',
                    'icon' => 'pencil',
                    'icon_floating' => 'pencil',
                    'authorized' => Auth::user()->can('update', $person)
                ],
                'delete' => [
                    'url' => route('people.destroy', $person),
                    'caption' => 'Delete',
                    'icon' => 'trash',
                    'authorized' => Auth::user()->can('delete', $person),
                    'confirmation' => 'Really delete this person?'
                ],
                'back' => [
                    'url' => route($this->getPeopleOverviewRouteName()),
                    'caption' => 'Close',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', Person::class)
                ]
            ]
        ]);
    }

    public function edit(Person $person) {
        $this->authorize('update', $person);

        return view('people.edit', [
            'person' => $person,
            'buttons' => [
                'back' => [
                    'url' => route('people.show', $person),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('view', $person)
                ]
            ]
		]);
	}

	public function update(StorePerson $request, Person $person) {
        $this->authorize('update', $person);

        $person->name = $request->name;
        $person->family_name = $request->family_name;
        $person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
        $person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->medical_no = !empty($request->medical_no) ? $request->medical_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
        $person->languages = !empty($request->languages) ? $request->languages : null;
        $person->skills = !empty($request->skills) ? $request->skills : null;
        $person->save();
        return redirect()->route('people.show', $person)
                ->with('success', 'Person has been updated!');
	}

    public function destroy(Person $person) {
        $this->authorize('delete', $person);

        $person->delete();
        return redirect()->route($this->getPeopleOverviewRouteName())
            ->with('success', 'Person has been deleted!');
    }

	public function filter(Request $request) {
        $this->authorize('list', Person::class);

        $condition = [];
        foreach (['name', 'family_name', 'case_no', 'medical_no', 'registration_no', 'section_card_no', 'remarks', 'nationality', 'languages', 'skills', 'date_of_birth'] as $k) {
            if (!empty($request->$k)) {
                $condition[] = [$k, 'LIKE', '%' . $request->$k . '%'];
            }
        }
        return $persons = Person
            ::where($condition)
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', self::DEFAULT_RESULTS_PER_PAGE));
	}
    
    public function export() {
        $this->authorize('list', Person::class);

        \Excel::create('OHF_Community_' . Carbon::now()->toDateString(), function($excel) {
            $dm = Carbon::create();
            $excel->sheet($dm->format('F Y'), function($sheet) use($dm) {
                $persons = Person::orderBy('name', 'asc')
                    ->orderBy('family_name', 'asc')
                    ->get();
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('people.export',[
                    'persons' => $persons
                ]);
            });
        })->export('xls');
    }

    function import() {
        $this->authorize('create', Person::class);

        return view('people.import', [
            'buttons' => [
                'back' => [
                    'url' => route('people.index'),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', Person::class)
                ]
            ]
		]);
    }

    function doImport(Request $request) {
        $this->authorize('create', Person::class);

        $this->validate($request, [
            'file' => 'required|file',
        ]);
        $file = $request->file('file');
        
        \Excel::selectSheets()->load($file, function($reader) {
            
            \DB::table('transactions')->delete();
            \DB::table('persons')->delete();

            $reader->each(function($sheet) {
            
                // Loop through all rows
                $sheet->each(function($row) {
                    
                    if (!empty($row->name)) {
                        $person = Person::create([
                            'name' => $row->name,
                            'family_name' => isset($row->surname) ? $row->surname : $row->family_name,
                            'case_no' => is_numeric($row->case_no) ? $row->case_no : null,
                            'medical_no' => isset($row->medical_no) ? $row->medical_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
                            'nationality' => $row->nationality,
                            'languages' => $row->languages,
                            'skills' => $row->skills,
                            'remarks' => !is_numeric($row->case_no) && empty($row->remarks) ? $row->case_no : $row->remarks,
                        ]);
                    }
                });

            });
        });
		return redirect()->route('people.index')
				->with('success', 'Import successful!');		
    }

    function charts() {
        $data = [];
        $nationalities = collect(
            Person
                    ::select('nationality', \DB::raw('count(*) as total'))
                    ->groupBy('nationality')
                    ->whereNotNull('nationality')
                    ->orderBy('total', 'DESC')
                    ->get()
            )->mapWithKeys(function($i){
                return [$i['nationality'] => $i['total']];
            });
        $data['nationalities'] = $nationalities->slice(0,6)->toArray();
        $data['nationalities']['Other'] = $nationalities->slice(6)->reduce(function ($carry, $item) {
            return $carry + $item;
        });
		
		$data['registrations'] = $this->getRegistrationsPerDay(90);

        return view('people.charts', [
            'data' => $data,
            'colors' => [
                "red",
                "orange",
                "yellow",
                "green",
                "cyan",
                "blue",
                "purple"
            ],
            'buttons' => [
                'back' => [
                    'url' => route('people.index'),
                    'caption' => 'Close',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', Person::class)
                ]
            ]
		]);
    }
	
	function getRegistrationsPerDay($numDays) {
		$registrations = Person::where('created_at', '>=', Carbon::now()->subDays($numDays))
			->groupBy('date')
			->orderBy('date', 'DESC')
			->get(array(
				\DB::raw('Date(created_at) as date'),
				\DB::raw('COUNT(*) as "count"')
			))
			->mapWithKeys(function ($item) {
				return [$item['date'] => $item['count']];
			})
			->reverse()
			->all();
		for ($i=1; $i < $numDays; $i++) {
			$dateKey = Carbon::now()->subDays($i)->toDateString();
			if (!isset($registrations[$dateKey])) {
				$registrations[$dateKey] = 0;
			}
		}
		ksort($registrations);
		return $registrations;
	}
}
