<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StoreTransaction;
use App\Http\Requests\StoreTransactionSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        session(['peopleOverviewRouteName' => 'bank.index']);

		return view('bank.index', [
			'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT),
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
                    'url' => route('bank.charts'),
                    'caption' => 'Charts',
                    'icon' => 'line-chart',
                    'authorized' => true
                ],
                [
                    'url' => route('bank.export'),
                    'caption' => 'Export',
                    'icon' => 'download',
                    'authorized' => Auth::user()->can('list', Person::class)
                ],
                [
                    'url' => route('bank.import'),
                    'caption' => 'Import',
                    'icon' => 'upload',
                    'authorized' => Auth::user()->can('create', Person::class)
                ],
                [
                    'url' => route('bank.settings'),
                    'caption' => 'Settings',
                    'icon' => 'cogs',
                    'authorized' => Gate::allows('use-bank')
                ],
            ]
		]);
    }

    function settings() {
		return view('bank.settings', [
			'transaction_default_value' => \Setting::get('bank.transaction_default_value', self::TRANSACTION_DEFAULT_VALUE),
			'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT),
			'boutique_threshold_days' => \Setting::get('bank.boutique_threshold_days', self::BOUTIQUE_THRESHOLD_DAYS),
            'people_results_per_page' => \Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE),
            'buttons' => [
                'back' => [
                    'url' => route('bank.index'),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Gate::allows('use-bank')
                ]
            ]
		]);
    }

	function updateSettings(StoreTransactionSettings $request) {
		\Setting::set('bank.transaction_default_value', $request->transaction_default_value);
		\Setting::set('bank.single_transaction_max_amount', $request->single_transaction_max_amount);
		\Setting::set('bank.boutique_threshold_days', $request->boutique_threshold_days);
        \Setting::set('people.results_per_page', $request->people_results_per_page);
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
            'data' => $data,
            'buttons' => [
                'back' => [
                    'url' => route('bank.index'),
                    'caption' => 'Close',
                    'icon' => 'times-circle',
                    'authorized' => Gate::allows('use-bank')
                ]
            ]
		]);
    }

    function import() {
		return view('bank.import', [
            'buttons' => [
                'back' => [
                    'url' => route('bank.index'),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Gate::allows('use-bank')
                ]
            ]
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
                            'medical_no' => isset($row->medical_no) ? $row->medical_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
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
            ->select('persons.id', 'name', 'family_name', 'case_no', 'medical_no', 'registration_no', 'section_card_no', 'nationality', 'remarks', 'boutique_coupon')
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE));
         
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
                        'medical_no' => $item->medical_no,
                        'registration_no' => $item->registration_no,
                        'section_card_no' => $item->section_card_no,
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
				$date = $coupon_date->addDays(self::getBoutiqueThresholdDays());
				if ($date->diffInDays() > 5) {
					return $date->diffInDays() . ' days from now';
				}
				return $date->diffForHumans();
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
                    'medical_no' => $person->medical_no,
                    'registration_no' => $person->registration_no,
                    'section_card_no' => $person->section_card_no,
                    'nationality' => $person->nationality, 
                    'remarks' => $person->remarks,
					'boutique_coupon' => self::getBoutiqueCouponForJson($person, $boutique_date_threshold),
                    'today' => $person->todaysTransaction(),
                    'yesterday' => $person->yesterdaysTransaction()
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
                $persons = Person::orderBy('name', 'asc')
                    ->orderBy('family_name', 'asc')
                    ->get();
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
