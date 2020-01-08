<?php

namespace Modules\Bank\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Bank\Entities\CouponType;
use Modules\Bank\Entities\CouponHandout;
use Modules\Bank\Http\Requests\StoreHandoutCoupon;
use Modules\Bank\Http\Requests\StoreUndoHandoutCoupon;
use Modules\Bank\Transformers\BankPerson;
use Modules\Bank\Transformers\BankPersonCollection;
use Modules\Bank\Util\BankStatistics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class BankController extends Controller
{
    public function dailyStats()
    {
        $numberOfPersonsServed = BankStatistics::getNumberOfPersonsServedToday();
        $numberOfCouponsHandedOut = BankStatistics::getNumberOfCouponsHandedOut();
        $todaysDailySpendingLimitedCoupons = BankStatistics::getTodaysDailySpendingLimitedCoupons();

		return response()->json([
            // 'numberOfPersonsServed' => $numberOfPersonsServed,
            // 'numberOfCouponsHandedOut' => $numberOfCouponsHandedOut,
            'numbers' => __('people::people.num_persons_served_handing_out_coupons', [
                'persons' => $numberOfPersonsServed,
                'coupons' => $numberOfCouponsHandedOut,
            ]),
            // 'todaysDailySpendingLimitedCoupons' => BankStatistics::getTodaysDailySpendingLimitedCoupons(),
            'limitedCoupons' => collect($todaysDailySpendingLimitedCoupons)->map(function($coupon, $couponName){
                return __('bank::coupons.coupons_handed_out_n_t', [ 'coupon' => $couponName, 'count' => $coupon['count'], 'limit' => $coupon['limit'] ]);
            })->values()
		]);
    }

    /**
     * View for showing search results.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $request->validate([
            'filter' => [
                'required'
            ]
        ]);

        $filter = $request->filter;

        // Create query
        $q = Person::orderBy('name', 'asc')
            ->orderBy('family_name', 'asc');

        // Handle OR keyword
        $terms = [];
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
                       $wq->orWhere('case_no_hash', DB::raw("SHA2('". $term ."', 256)"));
                    });
                }
            });
        }
        $limit = Config::get('bank.results_per_page');
        $results = $q->paginate($limit);

        $registerQuery = self::createRegisterStringFromFilter($filter);

        $couponTypes = CouponType::where('enabled', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();


        return (new BankPersonCollection($results))
            ->withCouponTypes($couponTypes)
            ->additional(['meta' => [
                'register_query' => $registerQuery,
            ]]);
    }

    private static function createRegisterStringFromFilter($filter)
    {
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
     * Handout coupon to person.
     *
     * @param  \App\Http\Requests\People\Bank\StoreHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function handoutCoupon(Person $person, CouponType $couponType, StoreHandoutCoupon $request) {
        $coupon = new CouponHandout();
        $coupon->date = Carbon::today();
        $coupon->amount = $request->amount;
        $coupon->code = $request->code;
        $coupon->person()->associate($person);
        $coupon->couponType()->associate($couponType);
        $coupon->save();

        return response()->json([
            'countdown' => $person->canHandoutCoupon($couponType),
            'return_grace_period' => $coupon->returGracePeriod,
            'message' => trans_choice('bank::coupons.coupon_has_been_handed_out_to', $coupon->amount, [
                'amount' => $coupon->amount,
                'coupon' => $couponType->name,
                'person' => $person->full_name,
            ])
        ]);
    }

    /**
     * Undo handing out coupon to person.
     *
     * @param  \App\Http\Requests\People\Bank\StoreUndoHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function undoHandoutCoupon(Person $person, CouponType $couponType, StoreUndoHandoutCoupon $request) {
        $handout = $person->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->orderBy('date', 'desc')
            ->first();
        if ($handout != null) {
            $handout->delete();
        }
        return response()->json([
            'message' => __('bank::coupons.coupon_has_been_taken_back_from', [
                'coupon' => $couponType->name,
                'person' => $person->full_name,
            ]),
        ]);
    }
}
