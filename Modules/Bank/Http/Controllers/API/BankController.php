<?php

namespace Modules\Bank\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Bank\Entities\CouponType;
use Modules\Bank\Entities\CouponHandout;
use Modules\Bank\Http\Requests\StoreHandoutCoupon;
use Modules\Bank\Http\Requests\StoreUndoHandoutCoupon;

use Carbon\Carbon;

class BankController extends Controller
{
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
