<?php

namespace Modules\Shop\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Bank\Entities\CouponType;
use Modules\Bank\Entities\CouponHandout;

use Modules\Shop\Http\Requests\DoCheckIn;
use Modules\Shop\Http\Requests\AddPerson;
use Modules\Shop\Http\Requests\RemovePerson;

use Carbon\Carbon;

class BarberShopController extends Controller
{
    public function checkin(DoCheckIn $request, Person $person) {
        if (\Setting::has('shop.barber.coupon_type')) {
            $handout = $person->couponHandouts()->whereDate('date', Carbon::today())
                ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type'))
                ->first();
            if ($handout != null) {
                $now = Carbon::now();
                $handout->code_redeemed = $now;
                $handout->save();
                return response()->json([
                    'time' => $now->format('H:i'),
                    'message' => __('shop::shop.checked_in_person', [ 'person' => $person->fullName ]),
                ]);
            }
            return response()->json([], 404);
        }
        return response()->json([], 405);
    }

}
