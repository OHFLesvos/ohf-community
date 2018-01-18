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
use Illuminate\Support\Facades\DB;

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

        $redirectFilter[] = $person->search;

        if (isset($request->child_family_name) && is_array($request->child_family_name)) {
            for ($i = 0; $i < count($request->child_family_name); $i++) {
                if (!empty($request->child_family_name[$i]) && !empty($request->child_name[$i]) && !empty($request->child_gender[$i])) {
                    $child = new Person();
                    $child->name = $request->child_name[$i];
                    $child->family_name = $request->child_family_name[$i];
                    $child->gender = $request->child_gender[$i];
                    $child->date_of_birth = !empty($request->child_date_of_birth[$i]) ? $request->child_date_of_birth[$i] : null;

                    $child->police_no = !empty($request->police_no) ? $request->police_no : null;
                    $child->case_no = !empty($request->case_no) ? $request->case_no : null;
                    $child->medical_no = !empty($request->medical_no) ? $request->medical_no : null;
                    $child->registration_no = !empty($request->registration_no) ? $request->registration_no : null;
                    $child->section_card_no = !empty($request->section_card_no) ? $request->section_card_no : null;
                    $child->temp_no = !empty($request->temp_no) ? $request->temp_no : null;
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
        return view('people.charts', [
            'nationalities' => self::getNationalities(),
            'gender' => self::getGenderDistribution(),
            'demographics' => self::getDemographics(),
		]);
    }

    /**
     * Nationalities
     */
    function nationalities() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getNationalities())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getNationalities($limit = 6) {
        $nationalities = collect(
            Person::select('nationality', \DB::raw('count(*) as total'))
                    ->groupBy('nationality')
                    ->whereNotNull('nationality')
                    ->orderBy('total', 'DESC')
                    ->get()
            )->mapWithKeys(function($i){
                return [$i['nationality'] =>  $i['total']];
            });
        $data = $nationalities->slice(0, $limit)->toArray();
        $data['Other'] = $nationalities->slice($limit)->reduce(function ($carry, $item) {
                 return $carry + $item;
            });
        return $data;
    }

    /**
     * Gender distribution
     */
    function genderDistribution() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getGenderDistribution())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getGenderDistribution() {
        return collect(
            Person::select('gender', \DB::raw('count(*) as total'))
                    ->groupBy('gender')
                    ->whereNotNull('gender')
                    ->get()
            )->mapWithKeys(function($i){
                if ($i['gender'] == 'm') {
                    $label = 'Male';
                } else if ($i['gender'] == 'f') {
                    $label = 'Female';
                } else {
                    $label = $i['gender'];
                }
                return [$label =>  $i['total']];
            })
            ->toArray();
    }

    /**
     * Demographics
     */
    function demographics() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getDemographics())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getDemographics() {
        return [
            'Toddlers (0-4)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            'Children (5-11)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(12))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            'Adolescents (12-17)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(18))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(12))
                ->select('date_of_birth')
                ->count(),            
            'Adults (18+)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(18))
                ->select('date_of_birth')
                ->count(),            
        ];
    }

    /**
     * Visitors per day
     */
    function visitorsPerDay() {
        $from = Carbon::now()->subMonth(3);
        $to = Carbon::now();
        $data = self::getvisitorsPerDay($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerDay($from, $to) {
        return self::createDateCollectionEmpty($from, $to)
            ->merge(self::getVisitorsPerDayQuery($from, $to)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->visitors];
                }))
            ->reverse()
            ->toArray();
    }

    private static function createDateCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            $dates[$date->toDateString()] = null;
        } while ($date->subDay()->gt($from));
        return collect($dates);
    }

    /**
     * Visitors per week
     */
    function visitorsPerWeek() {
        $from = Carbon::now()->subMonth(6)->startOfWeek();
        $to = Carbon::now();
        $data = self::getvisitorsPerWeek($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerWeek($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createWeekCollectionEmpty($from, $to)
            ->merge(
                // MySQL week number formats: https://www.w3resource.com/mysql/date-and-time-functions/mysql-week-function.php
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('CONCAT(LPAD(WEEK(date, 3), 2, 0), \' / \', YEAR(date)) as week'), DB::raw('SUM(visitors) as visitors'))
                    ->groupBy(DB::raw('WEEK(date, 3)'), DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->week => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    private static function createWeekCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('W / Y')] = null;
        } while ($date->subWeek()->gt($from));
        return collect($dates);
    }

    /**
     * Visitors per month
     */
    function visitorsPerMonth() {
        $from = Carbon::now()->subMonth(12)->startOfMonth();
        $to = Carbon::now();
        $data = self::getvisitorsPerMonth($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerMonth($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createMonthCollectionEmpty($from, $to)
            ->merge(
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('DATE_FORMAT(date, \'%M %Y\') as month'), DB::raw('SUM(visitors) as visitors')) // CONCAT(MONTH(date), \'/\', YEAR(date))
                    ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->month => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    private static function createMonthCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('F Y')] = null;
        } while ($date->subWeek()->gt($from));
        return collect($dates);
    }

    private static function getVisitorsPerDayQuery($from, $to) {
        $personsQuery = DB::table('transactions')
            ->select(DB::raw('transactionable_id AS person_id'), DB::raw('DATE(created_at) AS date'), 'transactionable_type')
            ->groupBy(DB::raw('DAY(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'), 'transactionable_id')
            ->having('transactionable_type', 'App\Person')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        return DB::table(DB::raw('('.$personsQuery->toSql().') as o1'))
            ->select('date', DB::raw('COUNT(`person_id`) as visitors'))
            ->groupBy('date')
            ->mergeBindings($personsQuery);
    }

    /**
     * Registrations per day
     */
    function registrationsPerDay() {
        $data = self::getRegistrationsPerDay(91);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Registrations' => array_values($data),
            ]
        ]);
    }

    private static function getRegistrationsPerDay($numDays) {
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
