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
    function index() {
		return view('people.index', [
            'persons' => Person::orderBy('name', 'asc')->get()
		]);
    }
    
	public function filter(Request $request) {
        $filter = $request->filter;
        $condition = [];
        foreach (preg_split('/\s+/', $filter) as $q) {
            $condition[] = ['search', 'LIKE', '%' . $q . '%'];
        }
        $persons = Person
            ::where($condition)
            ->orderBy('name', 'asc')
            ->paginate(500);
        
        return response()->json([
            'count' => $persons->count(),
            'total' => $persons->total(),
            'results' => $persons->all()
        ]);
	}

}
