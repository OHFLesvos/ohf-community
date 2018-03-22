<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeposit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Person;
use App\RevokedCard;
use App\Http\Requests\UpdatePersonDateOfBirth;
use App\Http\Requests\UpdatePersonGender;
use App\Http\Requests\StoreHandoutCoupon;
use App\Http\Requests\StoreUndoHandoutCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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

    public function undoHandoutCoupon(StoreUndoHandoutCoupon $request) {
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

}
