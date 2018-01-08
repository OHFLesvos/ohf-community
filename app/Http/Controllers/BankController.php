<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeposit;
use App\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\Http\Requests\StoreTransaction;
use App\Http\Requests\StoreTransactionSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    const DIAPERS_THRESHOLD_DAYS = 1;

    const MONTHS_NO_TRANSACTIONS_SINCE = 2;

    private static function getSingleTransactionMaxAmount() {
		return \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT);
	}
	
	private static function getBoutiqueThresholdDays() {
		return \Setting::get('bank.boutique_threshold_days', self::BOUTIQUE_THRESHOLD_DAYS);
	}

    private static function getDiapersThresholdDays() {
		return \Setting::get('bank.diapers_threshold_days', self::DIAPERS_THRESHOLD_DAYS);
	}
    
    function index() {
        return view('bank.index');
    }

    function withdrawal(Request $request) {
        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'bank.withdrawal']);

        // Filter from request
		if (!empty($request->q)) {
			$request->session()->put('filter', $request->q);
		}

		return view('bank.withdrawal', [
            'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT),
		]);
    }

    public function todayStats() {
        return response()->json([
            'numberOfPersonsServed' => self::getNumberOfPersonsServedToday(),
            'transactionValue' => self::getTransactionValueToday(),
        ]);
    }

    public static function getNumberOfPersonsServedToday() {
        return Transaction::whereDate('transactions.created_at', '=', Carbon::today())
                //->where('transactionable_type', 'App\Person')
                ->join('persons', function ($join) {
                    $join->on('persons.id', '=', 'transactions.transactionable_id')
                        ->where('transactionable_type', 'App\Person')
                        ->whereNull('deleted_at');
                })
                ->groupBy('transactionable_id')
                ->havingRaw('sum(value) > 0')
                ->select('transactionable_id')
                ->get()
                ->count();
    }

    public static function getTransactionValueToday() {
        return (int)Transaction::whereDate('created_at', '=', Carbon::today())
                ->where('transactionable_type', 'App\Person')
                ->select(DB::raw('sum(value) as total'))
                ->get()
                ->first()
                ->total;
    }

    function settings() {
		return view('bank.settings', [
			'transaction_default_value' => \Setting::get('bank.transaction_default_value', self::TRANSACTION_DEFAULT_VALUE),
			'single_transaction_max_amount' => \Setting::get('bank.single_transaction_max_amount', self::SINGLE_TRANSACTION_MAX_AMOUNT),
			'boutique_threshold_days' => \Setting::get('bank.boutique_threshold_days', self::BOUTIQUE_THRESHOLD_DAYS),
            'people_results_per_page' => \Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE),
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

    function maintenance() {
        return view('bank.maintenance', [
            'months_no_transactions_since' => self::MONTHS_NO_TRANSACTIONS_SINCE,
            'people_without_transactions_since' => $this->getPeopleWithoutTransactionsSince(self::MONTHS_NO_TRANSACTIONS_SINCE),
            'people_without_transactions_ever' => $this->getPeopleWithoutTransactionsEver(),
            'people_without_number' => $this->getPeopleWithoutNumber(),
        ]);
    }

    /**
     * @param int $months number of months
     * @return int
     */
    private function getPeopleWithoutTransactionsSince($months): int
    {
        return Transaction::groupBy('transactionable_id')
            ->having(DB::raw('max(transactions.created_at)'), '<=', Carbon::today()->subMonth($months))
            ->join('persons', function ($join) {
                $join->on('persons.id', '=', 'transactions.transactionable_id')
                    ->where('transactionable_type', 'App\Person')
                    ->whereNull('deleted_at');
            })
            ->get()
            ->count();
    }

    /**
     * @return int
     */
    private function getPeopleWithoutTransactionsEver(): int
    {
        return Person::leftJoin('transactions', function ($join) {
            $join->on('persons.id', '=', 'transactions.transactionable_id')
                ->where('transactionable_type', 'App\Person')
                ->whereNull('deleted_at');
            })
            ->whereNull('transactions.id')
            ->get()
            ->count();
    }

    /**
     * @return int
     */
    private function getPeopleWithoutNumber(): int
    {
        return Person::whereNull('police_no')
            ->whereNull('case_no')
            ->whereNull('medical_no')
            ->whereNull('registration_no')
            ->whereNull('section_card_no')
            ->whereNull('temp_no')
            ->count();
    }

    function updateMaintenance(Request $request) {
	    $cnt = 0;
        if (isset($request->cleanup_no_transactions_since)) {
            $cnt += Person::destroy(Transaction::groupBy('transactionable_id')
                ->having(DB::raw('max(transactions.created_at)'), '<=', Carbon::today()->subMonth(self::MONTHS_NO_TRANSACTIONS_SINCE))
                ->having('transactionable_type', 'App\Person')
                ->get()
                ->map(function($item){
                    return $item->transactionable_id;
                })
                ->toArray()
            );
        }
        if (isset($request->cleanup_no_transactions_ever)) {
            $cnt += Person::destroy(Person::leftJoin('transactions', function($join){
                $join->on('persons.id', '=', 'transactions.transactionable_id')
                    ->where('transactionable_type', 'App\Person')
                    ->whereNull('deleted_at');
                })
                ->whereNull('transactions.id')
                ->select('persons.id')
                ->get()
                ->map(function($item){
                    return $item->id;
                })
                ->toArray()
            );
        }
        if (isset($request->cleanup_no_number)) {
            $cnt +=  Person::whereNull('police_no')
                ->whereNull('case_no')
                ->whereNull('medical_no')
                ->whereNull('registration_no')
                ->whereNull('section_card_no')
                ->whereNull('temp_no')
                ->delete();
        }
        return redirect()->route('bank.withdrawal')
            ->with('info', 'Removed ' . $cnt . ' records.');
    }

    function charts() {
        return view('bank.charts', [
            'projects' => Project::orderBy('name')
                ->where('enable_in_bank', true)
                ->get(),
            'avg_sum' => self::getAvgTransactionSumPerDay(),
            'highest_sum' => Transaction::
                select(DB::raw('sum(value) as sum, date(created_at) as date'))
                ->where('transactionable_type', 'App\\Person')
                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at)'))
                ->orderBy('sum', 'DESC')
                ->limit(1)
                ->first(),
            'last_month_sum' => self::sumOfTransactions(Carbon::today()->subMonth()->startOfMonth(), Carbon::today()->subMonth()->endOfMonth()),
            'this_month_sum' => self::sumOfTransactions(Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()),
            'last_week_sum' => self::sumOfTransactions(Carbon::today()->subWeek()->startOfWeek(), Carbon::today()->subWeek()->endOfWeek()),
            'this_week_sum' => self::sumOfTransactions(Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()),
            'today_sum' => self::sumOfTransactions(Carbon::today()->startOfDay(), Carbon::today()->endOfDay()),
        ]);
    }

    private static function getAvgTransactionSumPerDay() {
        $sub = Transaction::select(DB::raw('sum(value) as sum'))
            ->where('transactionable_type', 'App\\Person')
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at)'));
        return DB::table( DB::raw("({$sub->toSql()}) as sub") )
            ->select(DB::raw('round(avg(sum), 1) as avg'))
            ->mergeBindings($sub->getQuery())
            ->first()
            ->avg;
    }

    private static function sumOfTransactions($from, $to) {
        $transactions = Transaction::where('transactionable_type', 'App\Person')
            ->whereDate('created_at', '>=', $from->toDateString())
            ->whereDate('created_at', '<=', $to->toDateString())
            ->select('value')
            ->get();
        return (int)collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
    }

    function numTransactions() {
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $q = Transaction
                ::whereDate('created_at', '=', $day->toDateString())
				->where('transactionable_type', 'App\Person')
                ->select('value')
                ->get();
            $data['labels'][] = $day->toDateString();
            $data['datasets']['Transactions'][] = collect($q)
                ->count();
        }
		return response()->json($data);
    }

    function sumTransactions() {
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $q = Transaction
                ::whereDate('created_at', '=', $day->toDateString())
				->where('transactionable_type', 'App\Person')
                ->select('value')
                ->get();
            $data['labels'][] = $day->toDateString();
            $data['datasets']['Value'][] = collect($q)
                ->map(function($item){
                    return $item->value;
                })->sum();
        }
		return response()->json($data);
    }

    function import() {
		return view('bank.import');
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
                            'police_no' => is_numeric($row->police_no) ? $row->police_no : null,
                            'case_no' => is_numeric($row->case_no) ? $row->case_no : null,
                            'medical_no' => isset($row->medical_no) ? $row->medical_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
                            'temp_no' => isset($row->temp_no) ? $row->temp_no : null,
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
                                    $transaction->value = intval($v);
                                    $transaction->created_at = $d;
                                    $transaction->updated_at = $d;
                                    $person->transactions()->save($transaction, ['timestamps' => false]);
                                }
                            }
                        }
                    }
                });

            });
        });
		return redirect()->route('bank.withdrawal')
				->with('success', 'Import successful!');		
    }

	public function filter(Request $request) {
        $filter = $request->filter;
		$request->session()->put('filter', $filter);

		$terms = preg_split('/\s+/', $filter);
		
		$today = false;
		if (($key = array_search('today:', $terms)) !== false) {
			unset($terms[$key]);
			$today = true;
		}
		$filter = implode(' ', $terms);
		
        $condition = [];
        foreach ($terms as $q) {
            $condition[] = ['search', 'LIKE', '%' . $q . '%'];
        }
        if ($today) {
            $p = Person
                ::hasTransactionsToday()
                ->where($condition);
        } else {
            $p = Person
                ::where($condition);
        }
        $persons = $p
            ->select('persons.id', 'name', 'family_name', 'gender', 'date_of_birth', 'police_no', 'case_no', 'medical_no', 'registration_no', 'section_card_no', 'temp_no', 'nationality', 'remarks', 'boutique_coupon', 'diapers_coupon')
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE));
         
        return response()->json([
            'count' => $persons->count(),
            'total' => $persons->total(),
            'from' => $persons->firstItem(),
            'to' => $persons->lastItem(),
            'current_page' => $persons->currentPage(),
            'last_page' => $persons->lastPage(),
            'results' => collect($persons->all())
                ->map(function ($item) {
                    return self::getPersonArray($item);
                }),
            'register' => self::createRegisterStringFromFilter($filter),
			'rendertime' => round((microtime(true) - LARAVEL_START)*1000)
        ]);
	}

	public function resetFilter(Request $request) {
		$request->session()->forget('filter');
	}

	private static function getBoutiqueCouponForJson($person) {
		if ($person->boutique_coupon != null) {
            return static::calcCouponHandoutDate(self::getBoutiqueThresholdDays(), $person->boutique_coupon);
		}
		return null;
	}

    private static function getDiapersCouponForJson($person) {
		if ($person->diapers_coupon != null) {
            return static::calcCouponHandoutDate(self::getDiapersThresholdDays(), $person->diapers_coupon);
		}
		return null;
    }
    
    private static function calcCouponHandoutDate($day_treshold, $compare_date) {
        $coupon_date = (new Carbon($compare_date))->startOfDay();
        $threshold_date = Carbon::now()->subDays($day_treshold)->startOfDay();
        if ($coupon_date->gt($threshold_date)) {
            $days = $coupon_date->diffInDays($threshold_date);
            if ($days == 1) {
                return "tomorrow";
            }
            return $days . ' days from now';
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
        return response()->json(self::getPersonArray($person));
	}

    private static function getPersonArray(Person $person) {
        return [
            'id' => $person->id,
            'name' => $person->name,
            'family_name' => $person->family_name,
            'gender' => $person->gender,
            'age'=> $person->age,
            'police_no' => $person->police_no,
            'case_no' => $person->case_no,
            'medical_no' => $person->medical_no,
            'registration_no' => $person->registration_no,
            'section_card_no' => $person->section_card_no,
            'temp_no' => $person->temp_no,
            'nationality' => $person->nationality, 
            'remarks' => $person->remarks,
            'boutique_coupon' => self::getBoutiqueCouponForJson($person),
            'diapers_coupon' => self::getDiapersCouponForJson($person),
            'today' => $person->todaysTransaction(),
            'yesterday' => $person->yesterdaysTransaction()
        ];
    }

    public function storeTransaction(StoreTransaction $request) {
		$person = Person::find($request->person_id);
		if ($person ->todaysTransaction() + $request->value > self::getSingleTransactionMaxAmount()) {
			return response()->json(["Invalid amount, must be not greater than " . self::getSingleTransactionMaxAmount()], 400);
		}
		$transaction = new Transaction();
        $transaction->value = $request->value;
        $person->transactions()->save($transaction);
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
    
	public function updateGender(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null) {
				$person->gender = $request->gender;
				$person->save();
				return $this->person($person);
			}
		}
    }
    
	public function giveBoutiqueCoupon(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null) {
				$person->boutique_coupon = Carbon::now();
				$person->save();
				return $this->person($person);
			}
		}
    }
    
	public function giveDiapersCoupon(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null) {
				$person->diapers_coupon = Carbon::now();
				$person->save();
				return $this->person($person);
			}
		}
    }
    
    public function deposit() {
        $transactions = Transaction
                ::join('projects', function ($join) {
                    $join->on('projects.id', '=', 'transactions.transactionable_id')
                        ->where('transactionable_type', 'App\Project');
                })
                ->select('projects.name', 'value', 'transactions.created_at')
                ->orderBy('transactions.created_at', 'DESC')
                ->limit(10)
                ->get();
        
        return view('bank.deposit', [
            'projectList' => Project::orderBy('name')
                ->where('enable_in_bank', true)
                ->get()
                ->mapWithKeys(function($project){
                    return [$project->id => $project->name];
                }),
            'transactions' => $transactions,
        ]);
    }

    public function depositStats() {
        $days = 30;

        // Labels
        $lables = [];
        for ($i = $days; $i >= 0; $i--) {
            $lables[] = Carbon::today()->subDays($i)->format('D j. M');
        }
        $datasets = [];

        // Projects
        $projects = Project::orderBy('name')
            ->where('enable_in_bank', true)
            ->get();
        foreach ($projects as $project) {
            $transactions = [];
            for ($i = $days; $i >= 0; $i--) { 
                $transactions[] = $project->dayTransactions(Carbon::today()->subDays($i));
            }
            $datasets[$project->name] = $transactions;
        }

        return response()->json([
            'labels' => $lables,
            'datasets' => $datasets,
        ]);
    }

    public function projectDepositStats(Project $project) {
        $days = 30;

        // Labels
        $lables = [];
        for ($i = $days; $i >= 0; $i--) {
            $lables[] = Carbon::today()->subDays($i)->format('D j. M');
        }

        // Projects
        $datasets = [];
        $transactions = [];
        for ($i = $days; $i >= 0; $i--) { 
            $transactions[] = $project->dayTransactions(Carbon::today()->subDays($i));
        }
        $datasets[$project->name] = $transactions;

        return response()->json([
            'labels' => $lables,
            'datasets' => $datasets,
        ]);
    }

    public function storeDeposit(StoreDeposit $request) {
        $project = Project::find($request->project);
        $transaction = new Transaction();
        $transaction->value = $request->value;
		$date = new Carbon($request->date);
		if (!$date->isToday()) {
			$transaction->created_at = $date->endOfDay();
		}
        $project->transactions()->save($transaction);

        return redirect()->route('bank.deposit')
            ->with('info', 'Added ' . $transaction->value . ' drachma to project \'' . $project->name . '\'.');
    }
}
