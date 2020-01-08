<?php

namespace Modules\Bank\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;
use Modules\People\Entities\RevokedCard;

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
            'numbers' => __('people::people.num_persons_served_handing_out_coupons', [
                'persons' => $numberOfPersonsServed,
                'coupons' => $numberOfCouponsHandedOut,
            ]),
            'limitedCoupons' => collect($todaysDailySpendingLimitedCoupons)
                ->map(function($coupon, $couponName){
                    return __('bank::coupons.coupons_handed_out_n_t', [
                        'coupon' => $couponName,
                        'count' => $coupon['count'],
                        'limit' => $coupon['limit']
                    ]);
                })
                ->values()
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
                'required_without:card_no'
            ],
            'card_no' => [
                'required_without:filter'
            ]
        ]);

        $couponTypes = CouponType::where('enabled', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        if ($request->filled('card_no')) {
            return $this->getPersonByCardNo($request->card_no, $couponTypes);
        } else {
            return $this->getPersonsByFilter($request->filter, $couponTypes);
        }
    }

    private function getPersonByCardNo($cardNo, $couponTypes)
    {
        // Check for revoked card number
        $revoked = RevokedCard::where('card_no', $cardNo)->first();
        if ($revoked != null) {
            return response()->json([
                'message' => __('people::people.card_revoked', [
                    'card_no' => substr($cardNo, 0, 7),
                    'date' => $revoked->created_at
                ]),
            ]);
        }

        $person = Person::where('card_no', $cardNo)->first();
        if ($person != null) {
            return (new BankPerson($person))
                ->withCouponTypes($couponTypes);
        } else {
            return response()->json([]);
        }
    }

    private function getPersonsByFilter($filter, $couponTypes)
    {
        $q = Person::orderBy('name', 'asc')
            ->orderBy('family_name', 'asc');

        $terms = preg_split('/\s+/', $filter);
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

        $perPage = Config::get('bank.results_per_page');
        return (new BankPersonCollection($q->paginate($perPage)))
            ->withCouponTypes($couponTypes)
            ->additional(['meta' => [
                'register_query' => self::createRegisterStringFromFilter($filter),
                'terms' => $terms,
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
    public function handoutCoupon(Person $person, CouponType $couponType, StoreHandoutCoupon $request)
    {
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
    public function undoHandoutCoupon(Person $person, CouponType $couponType, StoreUndoHandoutCoupon $request)
    {
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
