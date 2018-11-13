<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;
use App\Http\Requests\Shop\DoCheckIn;
use App\Http\Requests\Shop\AddPerson;
use App\Http\Requests\Shop\RemovePerson;
use App\Person;
use App\CouponType;

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

        return view('shop.barber.index', [
            'list' => $list,
        ]);
    }

    public function checkin(DoCheckIn $request) {
        if (\Setting::has('shop.barber.coupon_type')) {
            $id = $request->person_id;
            $handout = CouponHandout::whereDate('date', Carbon::today())
                ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type'))
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
        return response()->json([], 405);
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
                        return $fail(__('shop.reservation_exists_already'));
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
            ->with('success', __('people.person_registered'));
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
                            return $fail(__('shop.reservation_does_not_exists'));
                        }
                    },
                ],
            ]);
    
            CouponHandout::whereDate('date', Carbon::today())
                            ->where('person_id', $request->person_id)
                            ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type', 0))
                            ->delete();
            
            return redirect()->route('shop.barber.index')
                ->with('success', __('shop.reservation_removed'));
        }
        return response()->json([], 403);
    }
}
