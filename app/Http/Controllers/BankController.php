<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Http\Requests\StorePerson;

class BankController extends Controller
{
    function index() {
		return view('bank', [
            'persons' => Person::orderBy('name', 'asc')->get()
		]);
    }

	public function store(StorePerson $request) {

        $person = new Person();
		$person->family_name = $request->family_name;
		$person->name = $request->name;
		$person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
		$person->case_no = !empty($request->case_no) ? $request->case_no : null;
		$person->remarks = !empty($request->remarks) ? $request->remarks : null;
		$person->nationality = !empty($request->nationality) ? $request->nationality : null;
		$person->languages = !empty($request->languages) ? $request->languages : null;
		$person->skills = !empty($request->skills) ? $request->skills : null;
		$person->save();

		return redirect()->route('bank.index')
				->with('success', 'Person has been added!');		
	}

	public function filter(Request $request) {
        $filter = $request->filter;
        $persons = Person
            ::where('name', 'LIKE', '%' . $filter . '%')
             ->orWhere('family_name', 'LIKE', '%' . $filter . '%')
             ->orWhere('case_no', 'LIKE', '%' . $filter . '%')
            ->select('name', 'family_name', 'case_no', 'nationality', 'remarks')
            ->orderBy('name', 'asc')
            ->get();
        return response()->json($persons);
	}

	public function export() {
        \Excel::create('Laravel Excel', function($excel) {
            $excel->sheet('Excel sheet', function($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->loadView('bank.table',[
                    'persons' => Person::orderBy('name', 'family_name')->get()
                ]);
            });
        })->export('xls');
    }
}
