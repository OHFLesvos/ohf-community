<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StorePerson;
use App\Http\Requests\StoreTransaction;

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

    function index() {
		return view('people.index', [
		]);
    }

    public function create() {
		return view('people.create', [
		]);
    }

	public function store(StorePerson $request) {
        $person = new Person();
		$person->name = $request->name;
		$person->family_name = $request->family_name;
		$person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
		$person->case_no = !empty($request->case_no) ? $request->case_no : null;
		$person->remarks = !empty($request->remarks) ? $request->remarks : null;
		$person->nationality = !empty($request->nationality) ? $request->nationality : null;
		$person->languages = !empty($request->languages) ? $request->languages : null;
		$person->skills = !empty($request->skills) ? $request->skills : null;
		$person->save();

		return redirect()->route('people.index')
				->with('success', 'Person has been added!');		
	}

	public function edit(Person $person) {
		return view('people.edit', [
            'person' => $person
		]);
	}

	public function update(StorePerson $request, Person $person) {
        if (isset($request->delete)) {
            $person->delete();
            return redirect()->route('people.index')
                    ->with('success', 'Person has been deleted!');		
        } else {
            $person->name = $request->name;
            $person->family_name = $request->family_name;
            $person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
            $person->case_no = !empty($request->case_no) ? $request->case_no : null;
            $person->remarks = !empty($request->remarks) ? $request->remarks : null;
            $person->nationality = !empty($request->nationality) ? $request->nationality : null;
            $person->languages = !empty($request->languages) ? $request->languages : null;
            $person->skills = !empty($request->skills) ? $request->skills : null;
            $person->save();
            
            return redirect()->route('people.index')
                    ->with('success', 'Person has been updated!');		
        }
	}

	public function filter(Request $request) {
        $condition = [];
        foreach (['name', 'family_name', 'case_no', 'remarks', 'nationality', 'languages', 'skills', 'date_of_birth'] as $k) {
            if (!empty($request->$k)) {
                $condition[] = [$k, 'LIKE', '%' . $request->$k . '%'];
            }
        }
        $persons = Person
            ::where($condition)
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(500);
        
        return response()->json([
            'count' => $persons->count(),
            'total' => $persons->total(),
            'results' => $persons->all(),
			'rendertime' => round((microtime(true) - LARAVEL_START)*1000)
        ]);
	}
    
    public function export() {
        \Excel::create('OHF_Community_' . Carbon::now()->toDateString(), function($excel) {
            $dm = Carbon::create();
            $excel->sheet($dm->format('F Y'), function($sheet) use($dm) {
                $persons = Person::orderBy('name', 'asc')->get();
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('people.export',[
                    'persons' => $persons
                ]);
            });
        })->export('xls');
    }

    function import() {
		return view('people.import', [
		]);
    }

    function doImport(Request $request) {
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
