<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StorePerson;
use App\Http\Requests\StoreTransaction;
use App\Http\Requests\StoreTransactionSettings;

class BankController extends Controller
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

	const TRANSACTION_DEFAULT_VALUE = 2;
	const SINGLE_TRANSACTION_MAX_AMOUNT = 2;
	const BOUTIQUE_THRESHOLD_DAYS = 7;
	
	private static function getSingleTransactionMaxAmount() {
		return \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT);
	}
	
	private static function getBoutiqueThresholdDays() {
		return \Setting::get('bank.boutique_threshold_days', self::BOUTIQUE_THRESHOLD_DAYS);
	}
	
    function index() {
		return view('bank.index', [
			'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT)
		]);
    }

    function settings() {
		return view('bank.settings', [
			'transaction_default_value' => \Setting::get('bank.transaction_default_value', self::TRANSACTION_DEFAULT_VALUE),
			'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT),
			'boutique_threshold_days' => \Setting::get('bank.boutique_threshold_days', self::BOUTIQUE_THRESHOLD_DAYS)
		]);
    }

	function updateSettings(StoreTransactionSettings $request) {
		\Setting::set('bank.transaction_default_value', $request->transaction_default_value);
		\Setting::set('bank.single_transaction_max_amount', $request->single_transaction_max_amount);
		\Setting::set('bank.boutique_threshold_days', $request->boutique_threshold_days);
		\Setting::save();
		return redirect()->route('bank.index')
                    ->with('success', 'Settings have been updated!');

	}
	
    function charts() {
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $q = Transaction
                ::whereDate('created_at', '=', $day->toDateString())
                ->select('value')
                ->get();
            $key = $day->format('Y-m-j (D)');
            $data['count'][$key] = collect($q)
                ->count();
            $data['sum'][$key] = collect($q)
                ->map(function($item){
                    return $item->value;
                })->sum();
        }
		return view('bank.charts', [
            'data' => $data
		]);
    }

    function register() {
		return view('bank.register', [
			'transaction_value' => \Setting::get('bank.transaction_default_value', self::TRANSACTION_DEFAULT_VALUE),
		]);
    }

    function import() {
		return view('bank.import', [
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

                //print_r($sheet->getTitle());
            
                // Loop through all rows
                $sheet->each(function($row) {
                    
                    if (!empty($row->name)) {
                        $person = Person::create([
                            'name' => $row->name,
                            'family_name' => isset($row->surname) ? $row->surname : $row->family_name,
                            'case_no' => is_numeric($row->case_no) ? $row->case_no : null,
                            'nationality' => $row->nationality,
                            'remarks' => !is_numeric($row->case_no) && empty($row->remarks) ? $row->case_no : $row->remarks,
                        ]);
                        foreach ($row as $k => $v) {
                            if (!empty($v)) {
                                $month = null;
                                if (is_numeric($k) && $k > 0) {
                                    $day = $k;
                                } else if (preg_match('/([0-9])+.([0-9])+./', $k, $m)) {
                                    $day = $m[1];
                                    $month = $m[2];
                                }
                                if (isset($day) && $day > 0) {
                                    $d = Carbon::createFromDate(null, $month, $day)->toDateTimeString();
                                    $transaction = new Transaction();
                                    $transaction->person_id = $person->id;
                                    $transaction->value = intval($v);
                                    $transaction->created_at = $d;
                                    $transaction->updated_at = $d;
                                    $transaction->save(['timestamps' => false]);
                                }
                            }
                        }
                    }
                });

            });
        });
		return redirect()->route('bank.index')
				->with('success', 'Import successful!');		
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

        if (!empty($request->value)) {
            $transaction = new Transaction();
            $transaction->person_id = $person->id;
            $transaction->value = $request->value;
            $transaction->save();
        }
        
        $request->session()->flash('filter', $person->name . ' ' . $person->family_name);

		return redirect()->route('bank.index')
				->with('success', 'Person has been added!');		
	}

	public function filter(Request $request) {
        $filter = $request->filter;
        $condition = [];
        foreach (preg_split('/\s+/', $filter) as $q) {
            $condition[] = ['search', 'LIKE', '%' . $q . '%'];
        }
        if (!empty($request->today)) {
            $p = Person
                ::hasTransactionsToday()
                ->where($condition);
        } else {
            $p = Person
                ::where($condition);
        }
        $persons = $p
            ->select('persons.id', 'name', 'family_name', 'case_no', 'nationality', 'remarks', 'boutique_coupon')
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(50);
         
		$boutique_date_threshold = Carbon::now()->subDays(self::getBoutiqueThresholdDays());
        return response()->json([
            'count' => $persons->count(),
            'total' => $persons->total(),
            'results' => collect($persons->all())
                ->map(function ($item) use ($boutique_date_threshold) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'family_name' => $item->family_name, 
                        'case_no' => $item->case_no, 
                        'nationality' => $item->nationality, 
                        'remarks' => $item->remarks,
						'boutique_coupon' => self::getBoutiqueCouponForJson($item, $boutique_date_threshold),
                        'today' => $item->todaysTransaction(),
                        'yesterday' => $item->yesterdaysTransaction()
                    ];
                }),
            'register' => self::createRegisterStringFromFilter($filter),
			'rendertime' => round((microtime(true) - LARAVEL_START)*1000)
        ]);
	}

	private static function getBoutiqueCouponForJson($person, $boutique_date_threshold) {
		if ($person->boutique_coupon != null) {
			$coupon_date = new Carbon($person->boutique_coupon);
			if ($coupon_date->gt($boutique_date_threshold)) {
				return $coupon_date->addDays(self::getBoutiqueThresholdDays())->diffForHumans();
			}
		}
		return null;
	}
	
    private static function createRegisterStringFromFilter($filter) {
        $register = [];
        $names = [];
        foreach (preg_split('/\s+/', $filter) as $q) {
            if (is_numeric($q)) {
                $register['case_no'] = $q;
            } else {
                $names[] = $q;
            }
        }
        if (sizeof($register) > 0 || sizeof($names) > 0) {
            if (sizeof($names) == 1) {
                $register['name'] = $names[0];
            } else {
                $register['family_name'] = array_pop($names);
                $register['name'] = implode(' ', $names);
            }

            array_walk($register, function(&$a, $b) { $a = "$b=$a"; });
            return implode('&', $register);
        }
        return null;
    }

	public function person(Person $person) {
		$boutique_date_threshold = Carbon::now()->subDays(self::getBoutiqueThresholdDays());
        return response()->json([
                    'id' => $person->id,
                    'name' => $person->name,
                    'family_name' => $person->family_name, 
                    'case_no' => $person->case_no, 
                    'nationality' => $person->nationality, 
                    'remarks' => $person->remarks,
					'boutique_coupon' => self::getBoutiqueCouponForJson($person, $boutique_date_threshold),
                    'today' => $person->todaysTransaction(),
                    'yesterday' => $person->yesterdaysTransaction()
        ]);
	}

	public function editPerson(Person $person) {
		return view('bank.edit', [
            'person' => $person
		]);
	}
    
    public function updatePerson(StorePerson $request, Person $person) {
        $person->family_name = $request->family_name;
        $person->name = $request->name;
        $person->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
        $person->case_no = !empty($request->case_no) ? $request->case_no : null;
        $person->remarks = !empty($request->remarks) ? $request->remarks : null;
        $person->nationality = !empty($request->nationality) ? $request->nationality : null;
        $person->languages = !empty($request->languages) ? $request->languages : null;
        $person->skills = !empty($request->skills) ? $request->skills : null;
        $person->save();

        $request->session()->flash('filter', $person->name . ' ' . $person->family_name);

        return redirect()->route('bank.index')
            ->with('success', 'Person has been updated!');
    }

    public function destroyPerson(Person $person) {
        $person->delete();
        return redirect()->route('bank.index')
            ->with('success', 'Person has been deleted!');
    }

	public function transactions(Person $person) {
		return view('bank.transactions', [
			'person' => $person,
			'transactions' => $person->transactions()
				->select('created_at', 'value')
				->orderBy('created_at', 'desc')
				->get()
		]);
	}

    public function storeTransaction(StoreTransaction $request) {
		$person = Person::find($request->person_id);
		if ($person ->todaysTransaction() + $request->value > self::getSingleTransactionMaxAmount()) {
			return response()->json(["Invalid amount, must be not greater than " . self::getSingleTransactionMaxAmount()], 400);
		}
		$transaction = new Transaction();
        $transaction->person_id = $request->person_id;
        $transaction->value = $request->value;
        $transaction->save();
        return $this->person($person);
    }
    
	public function export() {
        \Excel::create('OHF_Bank_' . Carbon::now()->toDateString(), function($excel) {
            $dm = Carbon::create();
            $excel->sheet($dm->format('F Y'), function($sheet) use($dm) {
                $persons = Person::orderBy('name', 'asc')->get();
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('bank.export',[
                    'persons' => $persons,
                    'year' => $dm->year,
                    'month' => $dm->month,
                    'day' => $dm->day,
                ]);
            });
        })->export('xls');
    }
	
	public function giveBoutiqueCoupon(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null) {
				$person->boutique_coupon = Carbon::now();
				$person->save();
				return 'OK';
			}
		}
	}
}
