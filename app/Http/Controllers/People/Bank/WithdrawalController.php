<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\CouponType;
use App\CouponHandout;
use App\Person;

class WithdrawalController extends Controller
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

    public function index(Request $request) {
        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'bank.withdrawal']);
        $request->session()->forget('filter');

		return view('bank.withdrawal', [
            'numberOfPersonsServed' => self::getNumberOfPersonsServedToday(),
            'numberOfCouponsHandedOut' => self::getNumberOfCouponsHandedOut(),
		]);
    }

    public static function getNumberOfPersonsServedToday() : int {
        $q = CouponHandout::whereDate('date', Carbon::today())
                ->groupBy('person_id')
                ->select('person_id');
        return DB::table(DB::raw('('.$q->toSql().') as o2'))
            ->mergeBindings($q->getQuery())
            ->count();
    }

    public static function getNumberOfCouponsHandedOut() : int {
        return (int)CouponHandout::whereDate('date', Carbon::today())
                ->select(DB::raw('sum(amount) as total'))
                ->get()
                ->first()
                ->total;
    }
    
    public function search(Request $request) {
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
        $results = $q->paginate(\Setting::get('people.results_per_page', Config::get('bank.results_per_page')));

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
            'undoGraceTime' => Config::get('bank.undo_grace_time'),
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

    public function transactions(Request $request) {
		return view('bank.transactions', [
            'transactions' => CouponHandout
                ::orderBy('created_at', 'DESC')
                ->with(['user', 'person', 'couponType'])
                ->paginate(100),
		]);
    }

}
