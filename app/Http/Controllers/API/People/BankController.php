<?php

namespace App\Http\Controllers\API\People;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponType;
use App\CouponHandout;
use Carbon\Carbon;
use App\Person;
use App\RevokedCard;
use App\Http\Requests\StoreHandoutCoupon;
use App\Http\Requests\StoreUndoHandoutCoupon;

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
