<?php

namespace App\Http\Controllers;

use App\Util\CountriesExtended;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StorePerson;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;

class PeopleController extends ParentController
{
    const DEFAULT_RESULTS_PER_PAGE = 15;
    const filter_fields = ['name', 'family_name', 'police_no', 'case_no', 'medical_no', 'registration_no', 'section_card_no', 'temp_no', 'remarks', 'nationality', 'languages', 'skills', 'date_of_birth'];

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
        $countries = CountriesExtended::getList('en');
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
		$person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
		$person->police_no = !empty($request->police_no) ? $request->police_no : null;
		$person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->medical_no = !empty($request->medical_no) ? $request->medical_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->temp_no = !empty($request->temp_no) ? $request->temp_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
		$person->languages = !empty($request->languages) ? $request->languages : null;
        $person->skills = !empty($request->skills) ? $request->skills : null;
		$person->save();

        $isBank = preg_match('/^bank./', session('peopleOverviewRouteName', 'people.index'));
		if ( $isBank ) {
            $request->session()->put('filter', $person->search);
            return redirect()->route('bank.withdrawalSearch')
                ->with('success', 'Person has been added!');
        }

		return redirect()->route(session('peopleOverviewRouteName', 'people.index'))
				->with('success', 'Person has been added!');		
	}

    public function show(Person $person) {
        return view('people.show', [
            'person' => $person,
            'transactions' => $person->transactions()
                ->select('created_at', 'value')
                ->orderBy('created_at', 'desc')
                ->paginate(),
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
            'countries' => CountriesExtended::getList('en')
		]);
	}

	public function update(StorePerson $request, Person $person) {
        $person->name = $request->name;
        $person->family_name = $request->family_name;
        $person->gender = $request->gender;
        $person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
        $person->police_no = !empty($request->police_no) ? $request->police_no : null;
        $person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->medical_no = !empty($request->medical_no) ? $request->medical_no : null;
        $person->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
        $person->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
        $person->temp_no = !empty($request->temp_no) ? $request->temp_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
        $person->languages = !empty($request->languages) ? $request->languages : null;
        $person->skills = !empty($request->skills) ? $request->skills : null;
        $person->save();
        return redirect()->route('people.show', $person)
                ->with('success', 'Person has been updated!');
	}

    public function destroy(Person $person) {
        $person->delete();
        return redirect()->route(session('peopleOverviewRouteName', 'people.index'))
            ->with('success', 'Person has been deleted!');
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

        return $persons = Person
            ::where($condition)
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc')
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

        return view('people.import');
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
                            'police_no' => is_numeric($row->police_no) ? $row->police_no : null,
                            'case_no' => is_numeric($row->case_no) ? $row->case_no : null,
                            'medical_no' => isset($row->medical_no) ? $row->medical_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
                            'temp_no' => isset($row->temp_no) ? $row->temp_no : null,
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

        // Nationalities
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

        // Registrations
		$data['registrations'] = $this->getRegistrationsPerDay(91);

		// Visits per week
        $visits_per_week = [];
		$date = Carbon::now()->endOfWeek();
		for ($i = 0; $i < 12; $i++) {
            $weekNum = $date->weekOfYear;
		    $val = 0;
            for ($d = 0; $d < 7; $d++) {
                $endDate = clone $date;
                $date->subDay(1);
                $val += Transaction::where('transactionable_type', 'App\Person')
                    ->whereBetween('created_at', [$date, $endDate])
                    ->groupBy('transactionable_id')
                    ->get()
                    ->count();
            }
            $visits_per_week[$weekNum] = $val;
        }
        $data['visits_per_week'] = array_reverse($visits_per_week, true);

        return view('people.charts', [
            'data' => $data,
            'visits_last_week' => count($data['visits_per_week']) > 1 ? array_slice($data['visits_per_week'], -2, 1)[0] : null,
            'visits_this_week' => count($data['visits_per_week']) > 0 ? array_slice($data['visits_per_week'], -1, 1)[0] : null,
		]);
    }
	
	function getRegistrationsPerDay($numDays) {
		$registrations = Person::where('created_at', '>=', Carbon::now()->subDays($numDays))
            ->withTrashed()
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
