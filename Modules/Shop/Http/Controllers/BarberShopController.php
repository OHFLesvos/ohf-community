<?php

namespace Modules\Shop\Http\Controllers;

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
    public function index() {
        $this->authorize('view-barber-list');

        $list = null;
        if (\Setting::has('shop.barber.coupon_type')) {
            $list = CouponHandout::whereDate('date', Carbon::today())
                ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type'))
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($e){
                    return [
                        'person' => $e->person,
                        'registered' => $e->created_at,
                        'redeemed' => $e->code_redeemed != null ? $e->updated_at : null,
                    ];
                });
        }

        return view('shop::barber.index', [
            'list' => $list,
        ]);
    }

    public function addPerson(AddPerson $request) {
        $request->validate([
            'person_id' => [
                function($attribute, $value, $fail) {
                    $existing = CouponHandout::whereDate('date', Carbon::today())
                        ->where('person_id', $value)
                        ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type', 0))
                        ->first();
                    if ($existing != null) {
                        return $fail(__('shop::shop.reservation_exists_already'));
                    }
                },
            ],
        ]);

        $person = Person::findOrFail($request->person_id);
        $couponType = CouponType::find(\Setting::get('shop.barber.coupon_type', 0));
    
        $coupon = new CouponHandout();
        $coupon->date = Carbon::today();
        $coupon->amount = 1;
        $coupon->person()->associate($person);
        $coupon->couponType()->associate($couponType);
        $coupon->save();
        
        return redirect()->route('shop.barber.index')
            ->with('success', __('people::people.person_registered'));
    }

    public function removePerson(RemovePerson $request) {
        if (\Setting::get('shop.barber.allow_remove', false)) {
            $request->validate([
                'person_id' => [
                    function($attribute, $value, $fail) {
                        $existing = CouponHandout::whereDate('date', Carbon::today())
                            ->where('person_id', $value)
                            ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type', 0))
                            ->first();
                        if ($existing == null) {
                            return $fail(__('shop::shop.reservation_does_not_exists'));
                        }
                    },
                ],
            ]);
    
            CouponHandout::whereDate('date', Carbon::today())
                            ->where('person_id', $request->person_id)
                            ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type', 0))
                            ->delete();
            
            return redirect()->route('shop.barber.index')
                ->with('success', __('shop::shop.reservation_removed'));
        }
        return response()->json([], 403);
    }
}
