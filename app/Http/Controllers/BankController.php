<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Transaction;
use App\Http\Requests\StorePerson;
use App\Http\Requests\StoreTransaction;

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
        $condition = [];
        foreach (preg_split('/\s+/', $filter) as $q) {
            $condition[] = ['search', 'LIKE', '%' . $q . '%'];
        }
        $persons = Person
            ::where($condition)
            ->select('id', 'name', 'family_name', 'case_no', 'nationality', 'remarks')
            ->orderBy('name', 'asc')
            //->limit(500)
            ->get();
        
        return response()->json(collect($persons)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'family_name' => $item->family_name, 
                    'case_no' => $item->case_no, 
                    'nationality' => $item->nationality, 
                    'remarks' => $item->remarks,
                    'today' => $item->todaysTransaction(),
                    'yesterday' => $item->yesterdaysTransaction()
                ];
            })
        );
	}

	public function person(Person $person) {
        return response()->json([
                    'id' => $person->id,
                    'name' => $person->name,
                    'family_name' => $person->family_name, 
                    'case_no' => $person->case_no, 
                    'nationality' => $person->nationality, 
                    'remarks' => $person->remarks,
                    'today' => $person->todaysTransaction(),
                    'yesterday' => $person->yesterdaysTransaction()
        ]);
	}
    
    public function storeTransaction(StoreTransaction $request) {
        $transaction = new Transaction();
        $transaction->person_id = $request->person_id;
        $transaction->value = $request->value;
        $transaction->save();
        return response()->json(["OK"]);
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
