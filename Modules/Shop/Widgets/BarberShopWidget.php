<?php

namespace Modules\Shop\Widgets;

use App\CouponHandout;
use App\Widgets\Widget;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class BarberShopWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('view-barber-list');
    }

    function view(): string
    {
        return 'shop::dashboard.widgets.barber-shop';
    }

    function args(): array {
        $num_reservations = 0;
        if (\Setting::has('shop.barber.coupon_type')) {
            $num_reservations = CouponHandout::whereDate('date', Carbon::today())
                ->where('coupon_type_id', \Setting::get('shop.barber.coupon_type'))
                ->count();
        }
        return [
            'num_reservations' => $num_reservations,
        ];
    }
}