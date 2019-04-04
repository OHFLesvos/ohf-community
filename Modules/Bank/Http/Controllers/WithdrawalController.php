<?php

namespace Modules\Bank\Http\Controllers;

use App\CouponType;
use App\Person;
use App\RevokedCard;
use App\Http\Controllers\Controller;

use Modules\Bank\Util\BankStatistics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use OwenIt\Auditing\Models\Audit;

class WithdrawalController extends Controller
{
    /**
     * View for searching persons.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // Remember this screen for back button on person details screen
        session(['peopleOverviewRouteName' => 'bank.withdrawal']);
        $request->session()->forget('filter');

		return view('bank::withdrawal', [
            'numberOfPersonsServed' => BankStatistics::getNumberOfPersonsServedToday(),
            'numberOfCouponsHandedOut' => BankStatistics::getNumberOfCouponsHandedOut(),
		]);
    }

    /**
     * View for showing search results.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        // Handle OR keyword
        foreach(preg_split('/\s+OR\s+/', $filter) as $orTerm) {
            $terms = preg_split('/\s+/', $orTerm);
            $q->orWhere(function($aq) use ($terms){
                foreach ($terms as $term) {
                    // Remove dash "-" from term
                    $term = preg_replace('/^([0-9]+)-([0-9]+)/', '$1$2', $term);
                    // Create like condition
                    $aq->where(function($wq) use ($term) {
                       $wq->where('search', 'LIKE', '%' . $term . '%'); 
                       $wq->orWhere('police_no', $term);
                       $wq->orWhere('registration_no', $term);
                       $wq->orWhere('section_card_no', $term);
                       $wq->orWhere('case_no_hash', DB::raw("SHA2('". $term ."', 256)"));
                    });
                }
            });
        }
        $limit = \Setting::get('people.results_per_page', Config::get('bank.results_per_page'));
        $results = $q->paginate($limit);

        $message = null;

		return view('bank::withdrawal-results', [
            'filter' => $request->filter,
            'results' => $results,
            'register' => self::createRegisterStringFromFilter($filter),
            'message' => $message,
            'undoGraceTime' => Config::get('bank.undo_grace_time'),
            'couponTypes' => CouponType
                ::where('enabled', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get(),
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

    /**
     * View for showing coupon transactions.
     * 
     * @return \Illuminate\Http\Response
     */
    public function transactions() {
		return view('bank::withdrawal.transactions', [
            'transactions' => Audit
                ::where('auditable_type', 'App\CouponHandout')
                ->orderBy('created_at', 'DESC')
                ->paginate(50),
		]);
    }

    public function showCard(String $cardNo) {
        // TODO validation

        // Check for revoked card number
        $revoked = RevokedCard::where('card_no', $cardNo)->first();
        if ($revoked != null) {
            return view('bank::withdrawal.error', [
                'message' => __('people.card_revoked', [ 'card_no' => substr($cardNo, 0, 7), 'date' => $revoked->created_at ]),
            ]);
        }

        $person = Person::where('card_no', $cardNo)->first();
        if ($person != null) {
            return view('bank::withdrawal.showCard', [
                'person' => $person,
                'undoGraceTime' => Config::get('bank.undo_grace_time'),
                'couponTypes' => CouponType
                    ::where('enabled', true)
                    ->orderBy('order')
                    ->orderBy('name')
                    ->get(),
            ]);
        } else {
            return view('bank::withdrawal.noCard', [
                'cardNo' => $cardNo,
            ]);
        }
    }
}
