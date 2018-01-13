<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeposit;
use App\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use App\RevokedCard;
use App\Http\Requests\StoreTransaction;
use App\Http\Requests\StoreTransactionSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;
use Dompdf\Dompdf;

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
        $request->session()->forget('filter');

		return view('bank.withdrawal', [
            'stats' => [
                'numberOfPersonsServed' => self::getNumberOfPersonsServedToday(),
                'transactionValue' => self::getTransactionValueToday(),
            ],
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
    
    function withdrawalSearch(Request $request) {
        // Get filter or redirect to search start
        $filter = $request->filter;
        if (!isset($filter) || trim($filter) == '') {
            $sessionFilter = session('filter');
            if (isset($sessionFilter) && trim($sessionFilter) != '') {
                return redirect()->route('bank.withdrawalSearch', ['filter' => $sessionFilter]);
            }
            return redirect()->route('bank.withdrawal');
        }
        $request->session()->put('filter', $request->filter);

        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'bank.withdrawalSearch']);
    
        // Create query
        $terms = preg_split('/\s+/', $filter);
        $condition = [];
        foreach ($terms as $q) {
            $condition[] = ['search', 'LIKE', '%' . $q . '%'];
        }
        $results = Person::where($condition)
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->paginate(\Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE));

		return view('bank.withdrawal-results', [
            'filter' => $request->filter,
            'results' => $results,
            'register' => self::createRegisterStringFromFilter($filter),
            'boutiqueThresholdDays' => self::getBoutiqueThresholdDays(),
            'diapersThresholdDays' => self::getDiapersThresholdDays(),
		]);
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
                $register['family_name'] = array_shift($names);
                $register['name'] = implode(' ', $names);
            }

            array_walk($register, function(&$a, $b) { $a = "$b=$a"; });
            return implode('&', $register);
        }
        return null;
    }

    public function codeCard() {
        $codes = [];
        for ($i = 0; $i < 10 * 5; $i++) {
            $code = bin2hex(random_bytes(16));
            $codes[] = base64_encode(self::createQrCode($code, substr($code, 0, 7), 500));
        }
        $logo = base64_encode(file_get_contents(public_path() . '/img/logo_card.png'));
        $view = view('bank.codeCard', [
            'codes' => $codes,
            'logo' => $logo,
        ])->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($view);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->set_option('dpi', 300);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        return $dompdf->stream();
    }

    public static function createQrCode($value, $label, $size) {
        $qrCode = new QrCode($value);
        $qrCode->setSize($size);
        $qrCode->setLabel($label, 20, null, LabelAlignment::CENTER);   
        return $qrCode->writeString();
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
        $result = DB::table( DB::raw("({$sub->toSql()}) as sub") )
            ->select(DB::raw('round(avg(sum), 1) as avg'))
            ->mergeBindings($sub->getQuery())
            ->first();
        return $result != null ? $result->avg : null;
    }

    private static function sumOfTransactions($from, $to) {
        $result = Transaction::where('transactionable_type', 'App\Person')
            ->whereDate('created_at', '>=', $from->toDateString())
            ->whereDate('created_at', '<=', $to->toDateString())
            ->select(DB::raw('sum(value) as sum'))
            ->first();
        return $result != null ? $result->sum : null;
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

    public function storeTransaction(StoreTransaction $request) {
		$person = Person::find($request->person_id);
		if ($person ->todaysTransaction() + $request->value > self::getSingleTransactionMaxAmount()) {
			return response()->json(["Invalid amount, must be not greater than " . self::getSingleTransactionMaxAmount()], 400);
		}
		$transaction = new Transaction();
        $transaction->value = $request->value;
        $person->transactions()->save($transaction);
        return response()->json([
            'today' => $person->todaysTransaction(),
            'date' => $person->transactions()->orderBy('created_at', 'DESC')->first()->created_at->toDateTimeString(),
        ]);
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
				return response()->json([
                    
                ]);
			}
		}
    }
	public function registerCard(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null && isset($request->card_no)) {

                // Check for revoked card number
                if (RevokedCard::where('card_no', $request->card_no)->count() > 0) {
                    return response()->json([
                        'message' => 'Card number has been revoked',
                    ], 400);
                }

                // Check for used card number
                if (Person::where('card_no', $request->card_no)->count() > 0) {
                    return response()->json([
                        'message' => 'Card number already in use',
                    ], 400);
                }

                // If person already has a card number, revoke it
                if ($person->card_no != null) {
                    $revoked = new RevokedCard();
                    $revoked->card_no = $person->card_no;
                    $person->revokedCards()->save($revoked);
                }

                // Issue new card
                $person->card_no = $request->card_no;
                $person->card_issued = Carbon::now();
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
				return response()->json([
                    'countdown' => $person->getBoutiqueCouponForJson(self::getBoutiqueThresholdDays()),
                ]);
			}
		}
    }
    
	public function giveDiapersCoupon(Request $request) {
		if (isset($request->person_id) && is_numeric($request->person_id)) {
			$person = Person::find($request->person_id);
			if ($person != null) {
				$person->diapers_coupon = Carbon::now();
				$person->save();
				return response()->json([
                    'countdown' => $person->getDiapersCouponForJson(self::getDiapersThresholdDays()),
                ]);
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
