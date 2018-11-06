<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;

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
                return $e->person;
            });

        return view('shop.barber.index', [
            'list' => $list,
        ]);
    }
}
