<?php

namespace App\Http\Controllers\API\People;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponType;
use App\CouponHandout;
use Carbon\Carbon;
use App\Person;
use App\RevokedCard;
use App\Http\Requests\People\Bank\RegisterCard;
use App\Http\Requests\People\Bank\StoreHandoutCoupon;
use App\Http\Requests\People\Bank\StoreUndoHandoutCoupon;

class BankController extends Controller
{
    /**
     * Register code card with person.
     * 
     * @param  \App\Http\Requests\People\Bank\RegisterCard  $request
     * @return \Illuminate\Http\Response
     */
	public function registerCard(RegisterCard $request) {
        $person = Person::findOrFail($request->person_id);
        
        $this->authorize('update', $person);

        // Check for revoked card number
        if (RevokedCard::where('card_no', $request->card_no)->count() > 0) {
            return response()->json([
                'message' => __('people.card_revoked', [ 'card_no' => substr($request->card_no, 0, 7) ]),
            ], 400);
        }

        // Check for used card number
        if (Person::where('card_no', $request->card_no)->count() > 0) {
            return response()->json([
                'message' => __('people.card_already_in_use', [ 'card_no' => substr($request->card_no, 0, 7) ]),
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

    /**
     * Handout coupon to person.
     * 
     * @param  \App\Http\Requests\People\Bank\StoreHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function handoutCoupon(StoreHandoutCoupon $request) {
        $person = Person::findOrFail($request->person_id);
        $couponType = CouponType::find($request->coupon_type_id);

        $coupon = new CouponHandout();
        $coupon->date = Carbon::today();
        $coupon->amount = $request->amount;
        $coupon->person()->associate($person);
        $coupon->couponType()->associate($couponType);
        $coupon->save();

        $handout = $person->canHandoutCoupon($couponType);
        return response()->json([
            'countdown' => $handout != null ? $handout['message'] : '',
        ]);
    }

    /**
     * Undo handing out coupon to person.
     * 
     * @param  \App\Http\Requests\People\Bank\StoreUndoHandoutCoupon  $request
     * @return \Illuminate\Http\Response
     */
    public function undoHandoutCoupon(StoreUndoHandoutCoupon $request) {
        $person = Person::findOrFail($request->person_id);
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
