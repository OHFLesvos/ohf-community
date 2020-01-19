<?php

namespace Modules\Bank\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;
use Modules\People\Repositories\RevokedCardRepository;
use Modules\People\Repositories\PersonRepository;

use Modules\Bank\Entities\CouponType;
use Modules\Bank\Entities\CouponHandout;
use Modules\Bank\Http\Requests\StoreHandoutCoupon;
use Modules\Bank\Http\Requests\StoreUndoHandoutCoupon;
use Modules\Bank\Transformers\BankPerson;
use Modules\Bank\Transformers\BankPersonCollection;
use Modules\Bank\Transformers\WithdrawalTransaction;
use Modules\Bank\Util\BankStatisticsProvider;
use Modules\Bank\Repositories\CouponTypeRepository;
use Modules\Bank\Repositories\CouponHandoutRepository;

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
     * @param \Modules\Bank\Util\BankStatisticsProvider $stats
     * @return \Illuminate\Http\Response
     */
    public function dailyStats(BankStatisticsProvider $stats)
    {
		return response()->json([
            'numbers' => __('people::people.num_persons_served_handing_out_coupons', [
                'persons' => $stats->getNumberOfPersonsServed(),
                'coupons' => $stats->getNumberOfCouponsHandedOut(),
            ]),
            'limitedCoupons' => $stats->getCouponsWithSpendingLimit()
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
     * Returns withdrawal transactions ordered by date (latest first)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Bank\Repositories\CouponHandoutRepository $couponHandouts
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
                'message' => __('people::people.card_revoked', [
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
     * @param \Modules\People\Entities\Person $person
     * @param \Modules\Bank\Entities\CouponType $couponType
     * @param \Modules\Bank\Http\Requests\StoreHandoutCoupon  $request
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
     * @param \Modules\People\Entities\Person $person
     * @param \Modules\Bank\Entities\CouponType $couponType
     * @param  \Modules\Bank\Http\Requests\StoreUndoHandoutCoupon  $request
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
            'message' => __('bank::coupons.coupon_has_been_taken_back_from', [
                'coupon' => $couponType->name,
                'person' => $person->full_name,
            ]),
        ]);
    }
}
