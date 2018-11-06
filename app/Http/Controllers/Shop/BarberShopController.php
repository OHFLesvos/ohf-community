<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;
use App\Http\Requests\Shop\DoCheckIn;

class BarberShopController extends Controller
{
    const COUPON_TYPE_ID = 6;

    public function index() {
        $this->authorize('view-barber-list');

        $list = CouponHandout::whereDate('date', Carbon::today())
            ->where('coupon_type_id', self::COUPON_TYPE_ID)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($e){
                return [
                    'person' => $e->person,
                    'registered' => $e->created_at,
                    'redeemed' => $e->code_redeemed != null ? $e->updated_at : null,
                ];
            });

        return view('shop.barber.index', [
            'list' => $list,
        ]);
    }

    public function checkin(DoCheckIn $request) {
        $id = $request->person_id;
        $handout = CouponHandout::whereDate('date', Carbon::today())
            ->where('coupon_type_id', self::COUPON_TYPE_ID)
            ->where('person_id', $id)
            ->first();
        if ($handout != null) {
            $now = Carbon::now();
            $handout->code_redeemed = $now;
            $handout->save();
            return response()->json([
                'time' => $now->format('H:i'),
                'message' => __('shop.checked_in_person', [ 'person' => $handout->person->fullName ]),
            ]);
        }
        return response()->json([], 404);
    }
}
