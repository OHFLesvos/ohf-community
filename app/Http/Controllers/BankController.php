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
use App\Http\Requests\UpdatePersonDateOfBirth;
use App\Http\Requests\UpdatePersonGender;
use App\Http\Requests\StoreHandoutCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;
use Dompdf\Dompdf;
use App\CouponType;
use App\CouponHandout;

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

    const UNDO_GRACE_TIME = 5; // Minutes

    const MONTHS_NO_TRANSACTIONS_SINCE = 2;
 
    function index() {
        return view('bank.index');
    }

    function withdrawal(Request $request) {
        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'bank.withdrawal']);
        $request->session()->forget('filter');

		return view('bank.withdrawal', [
            'numberOfPersonsServed' => self::getNumberOfPersonsServedToday(),
            'numberOfCouponsHandedOut' => self::getNumberOfCouponsHandedOut(),
		]);
    }

    public static function getNumberOfPersonsServedToday() : int {
        $sql = CouponHandout::whereDate('date', Carbon::today())
                ->groupBy('person_id')
                ->select('person_id');
        return DB::table(DB::raw('('.$sql->toSql().') as o2'))
            ->select(DB::raw('count(*) as count'))
            ->mergeBindings($sql->getQuery())
            ->get()
            ->first()->count;
    }

    public static function getNumberOfCouponsHandedOut() : int {
        return (int)CouponHandout::whereDate('date', Carbon::today())
                ->select(DB::raw('sum(amount) as total'))
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
        $q = Person::orderBy('family_name', 'asc')
            ->orderBy('name', 'asc');
        $isCodeCard = preg_match('/[a-f0-9]{32}/', $filter);
        if ($isCodeCard) { // QR code card number
            $q->where('card_no', $filter);
        } else {
            // Handle OR keyword
            foreach(preg_split('/\s+OR\s+/', $filter) as $orTerm) {
                $terms = preg_split('/\s+/', $orTerm);
                $q->orWhere(function($aq) use ($terms){
                    foreach ($terms as $term) {
                        // Remove dash "-" from term
                        $term = preg_replace('/^([0-9]+)-([0-9]+)/', '$1$2', $term);
                        // Create like condition
                        $aq->where('search', 'LIKE', '%' . $term . '%');
                    }
                });
            }
        }
        $results = $q->paginate(\Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE));

        $message = null;

        // Check for revoked card number
        if ($isCodeCard && count($results) == 0) {
            $revoked = RevokedCard::where('card_no', $filter)->first();
            if ($revoked !=null) {
                $message = 'Card number ' . substr($filter, 0, 7) . ' has been revoked on ' . $revoked->created_at . '.';
            }
        }

		return view('bank.withdrawal-results', [
            'filter' => $request->filter,
            'results' => $results,
            'register' => self::createRegisterStringFromFilter($filter),
            'message' => $message,
            'undoGraceTime' => self::UNDO_GRACE_TIME,
            'couponTypes' => CouponType::orderBy('order')->orderBy('name')->get(),
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

    function withdrawalTransactions(Request $request) {
		return view('bank.transactions', [
            'transactions' => CouponHandout::orderBy('created_at', 'DESC')
                ->with(['user', 'person', 'couponType'])
                ->paginate(100),
		]);
    }

    public function prepareCodeCard() {
        return view('bank.prepareCodeCard');
    }

    public function createCodeCard(Request $request) {
        $pages = isset($request->pages) && is_numeric($request->pages) && $request->pages > 0 ? $request->pages : 1;

        $codes = [];
        for ($i = 0; $i < 10 * $pages; $i++) {
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
            'people_results_per_page' => \Setting::get('people.results_per_page', PeopleController::DEFAULT_RESULTS_PER_PAGE),
            'frequent_visitor_weeks' => \Setting::get('bank.frequent_visitor_weeks', Person::FREQUENT_VISITOR_WEEKS),
            'frequent_visitor_threshold' => \Setting::get('bank.frequent_visitor_threshold', Person::FREQUENT_VISITOR_THRESHOLD),
            'current_num_people' => Person::count(),
            'current_num_frequent_visitors' => Reporting\PeopleReportingController::getNumberOfFrequentVisitors(),
		]);
    }

	function updateSettings(StoreTransactionSettings $request) {
        \Setting::set('people.results_per_page', $request->people_results_per_page);
        \Setting::set('bank.frequent_visitor_weeks', $request->frequent_visitor_weeks);
        \Setting::set('bank.frequent_visitor_threshold', $request->frequent_visitor_threshold);
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
            ->where('worker', false)
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
            ->whereNull('boutique_coupon')
            ->whereNull('diapers_coupon')
            ->where('worker', false)
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
            ->where('worker', false)
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
                ->whereNull('boutique_coupon')
                ->whereNull('diapers_coupon')
                ->where('worker', false)
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
                ->where('worker', false)
                ->delete();
        }
        return redirect()->route('bank.withdrawal')
            ->with('info', 'Removed ' . $cnt . ' records.');
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
                                    $value = intval($v);
                                    // TODO
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
    
	public function export() {
        $this->authorize('export', Person::class);

        \Excel::create('Bank_' . Carbon::now()->toDateString(), function($excel) {
            $dm = Carbon::create();
            $excel->sheet($dm->format('F Y'), function($sheet) use($dm) {
                $persons = Person::orderBy('name', 'asc')
                    ->orderBy('family_name', 'asc')
                    ->orderBy('name', 'asc')
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
        })->export('xlsx');
    }
    
	public function updateGender(UpdatePersonGender $request) {
        $person = Person::find($request->person_id);
        if ($person != null) {
            $person->gender = $request->gender;
            $person->save();
            return response()->json([
                'gender' => $person->gender,
            ]);
        }
    }

	public function updateDateOfBirth(UpdatePersonDateOfBirth $request) {
        $person = Person::find($request->person_id);
        if ($person != null) {
            if (isset($request->date_of_birth) && (new Carbon($request->date_of_birth))->lte(Carbon::today())) {
                $person->date_of_birth = $request->date_of_birth;
                $person->save();
                return response()->json([
                    'date_of_birth' => $person->date_of_birth,
                    'age' => $person->age,
                ]);
            } else {
                return response()->json(["Invalid or empty date!"], 400);
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
                        'message' => 'Card number ' . substr($request->card_no, 0, 7) . ' has been revoked',
                    ], 400);
                }

                // Check for used card number
                if (Person::where('card_no', $request->card_no)->count() > 0) {
                    return response()->json([
                        'message' => 'Card number ' . substr($request->card_no, 0, 7) . ' is already in use',
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
				return response()->json([]);
			}
		}
    }

    public function handoutCoupon(StoreHandoutCoupon $request) {
        $person = Person::find($request->person_id);
        $couponType = CouponType::find($request->coupon_type_id);
        $coupon = new CouponHandout();
        $coupon->date = Carbon::today();
        $coupon->amount = $request->amount;
        $coupon->person()->associate($person);
        $coupon->couponType()->associate($couponType);
        $coupon->save();

        $daysUntil = ((clone $coupon->date)->addDays($couponType->retention_period))->diffInDays() + 1;
        return response()->json([
            'countdown' => trans_choice('people.in_n_days', $daysUntil, ['days' => $daysUntil]),
        ]);
    }

    public function undoHandoutCoupon(StoreHandoutCoupon $request) {
        $person = Person::find($request->person_id);
        $couponType = CouponType::find($request->coupon_type_id);
        $handout = $person->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->whereDate('date', Carbon::today())
            ->first();
        if ($handout != null) {
            $handout->delete();
        }
        return response()->json([
        ]);
    }


    public function deposit() {
        $transactions = Transaction
                ::join('projects', function ($join) {
                    $join->on('projects.id', '=', 'transactions.transactionable_id')
                        ->where('transactionable_type', 'App\Project');
                })
                ->select('projects.name', 'value', 'transactions.created_at', 'transactions.user_id')
                ->orderBy('transactions.created_at', 'DESC')
                ->paginate(10);
        
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
