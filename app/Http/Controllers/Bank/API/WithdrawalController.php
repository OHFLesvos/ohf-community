<?php

namespace App\Http\Controllers\Bank\API;

use App\Http\Controllers\Controller;

use App\Models\People\Person;
use App\Repositories\People\RevokedCardRepository;
use App\Repositories\People\PersonRepository;

use App\Models\Bank\CouponType;
use App\Models\Bank\CouponHandout;
use App\Http\Requests\Bank\StoreHandoutCoupon;
use App\Http\Requests\Bank\StoreUndoHandoutCoupon;
use App\Http\Resources\Bank\BankPerson;
use App\Http\Resources\Bank\BankPersonCollection;
use App\Http\Resources\Bank\WithdrawalTransaction;
use App\Util\Bank\BankStatisticsProvider;
use App\Repositories\Bank\CouponTypeRepository;
use App\Repositories\Bank\CouponHandoutRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WithdrawalController extends Controller
{
    private $revokedCards;
    private $persons;
    private $couponTypes;

    public function __construct(RevokedCardRepository $revokedCards, PersonRepository $persons, CouponTypeRepository $couponTypes)
    {
        $this->revokedCards = $revokedCards;
        $this->persons = $persons;
        $this->couponTypes = $couponTypes;
    }

    /**
     * Provides statistics from the current day
     *
     * @param \App\Util\Bank\BankStatisticsProvider $stats
     * @return \Illuminate\Http\Response
     */
    public function dailyStats(BankStatisticsProvider $stats)
    {
		return response()->json([
            'number_of_persons_served' => $stats->getNumberOfPersonsServed(),
            'number_of_coupons_handed_out' => $stats->getNumberOfCouponsHandedOut(),
            'limited_coupons' => $stats->getCouponsWithSpendingLimit()
                ->map(function($coupon, $couponName){
                    return [
                        'coupon' => $couponName,
                        'count' => $coupon['count'],
                        'limit' => $coupon['limit']
                    ];
                })
                ->values()
		]);
    }

    /**
     * Returns withdrawal transactions ordered by date (latest first)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Repositories\Bank\CouponHandoutRepository $couponHandouts
     * @return \Illuminate\Http\Response
     */
    public function transactions(Request $request, CouponHandoutRepository $couponHandouts)
    {
        $request->validate([
            'perPage' => [
                'nullable',
                'integer',
                'min:1'
            ]
        ]);

        $perPage = $request->input('perPage');

        if ($request->filled('filter')) {
            $terms = split_by_whitespace($request->input('filter'));
            $data = $couponHandouts->getAuditsFilteredByPerson($terms, $perPage);
        } else {
            $data = $couponHandouts->getAudits($perPage);
        }

        return WithdrawalTransaction::collection($data);
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
                'required_without_all:card_no'
            ],
            'card_no' => [
                'required_without_all:filter'
            ]
        ]);

        if ($request->filled('card_no')) {
            return $this->getPersonByCardNo($request->card_no);
        } else {
            return $this->getPersonsByFilter($request->filter);
        }
    }

    private function getPersonByCardNo($cardNo)
    {
        if (($revoked = $this->revokedCards->findByCardNumber($cardNo)) !== null) {
            return response()->json([
                'message' => __('people.card_revoked', [
                    'card_no' => substr($cardNo, 0, 7),
                    'date' => $revoked->created_at
                ]),
            ]);
        }

        if (($person = $this->persons->findByCardNumber($cardNo)) !== null) {
            return (new BankPerson($person))
                ->withCouponTypes($this->couponTypes->getEnabled());
        } else {
            return response()->json([]);
        }
    }

    private function getPersonsByFilter($filter)
    {
        $terms = split_by_whitespace($filter);
        $perPage = Config::get('bank.results_per_page');

        $data = $this->persons->filterByTerms($terms, $perPage);

        return (new BankPersonCollection($data))
            ->withCouponTypes($this->couponTypes->getEnabled())
            ->additional(['meta' => [
                'register_query' => self::createRegisterStringFromFilter($filter),
                'terms' => collect($terms)->unique()->values(),
            ]]);
    }

    private static function createRegisterStringFromFilter($filter)
    {
        $register = [];
        $names = [];
        foreach (split_by_whitespace($filter) as $q) {
            if (is_numeric($q)) {
                $register['police_no'] = $q;
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

    /**
     * Single person
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function person(Person $person)
    {
        return (new BankPerson($person))
            ->withCouponTypes($this->couponTypes->getEnabled());
    }

    /**
     * Handout coupon to person.
     *
     * @param \App\Models\People\Person $person
     * @param \App\Models\Bank\CouponType $couponType
     * @param \App\Http\Requests\Bank\StoreHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function handoutCoupon(Person $person, CouponType $couponType, StoreHandoutCoupon $request)
    {
        $coupon = new CouponHandout();
        $coupon->date = today();
        $coupon->amount = $request->amount;
        $coupon->code = $request->code;
        $coupon->person()->associate($person);
        $coupon->couponType()->associate($couponType);
        $coupon->save();

        return response()->json([
            'countdown' => $person->canHandoutCoupon($couponType),
            'return_grace_period' => $coupon->returGracePeriod,
            'message' => trans_choice('coupons.coupon_has_been_handed_out_to', $coupon->amount, [
                'amount' => $coupon->amount,
                'coupon' => $couponType->name,
                'person' => $person->full_name,
            ])
        ]);
    }

    /**
     * Undo handing out coupon to person.
     *
     * @param \App\Models\People\Person $person
     * @param \App\Models\Bank\CouponType $couponType
     * @param  \App\Http\Requests\Bank\StoreUndoHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function undoHandoutCoupon(Person $person, CouponType $couponType, StoreUndoHandoutCoupon $request)
    {
        $person->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->orderBy('date', 'desc')
            ->limit(1)
            ->delete();

        return response()->json([
            'message' => __('coupons.coupon_has_been_taken_back_from', [
                'coupon' => $couponType->name,
                'person' => $person->full_name,
            ]),
        ]);
    }
}
