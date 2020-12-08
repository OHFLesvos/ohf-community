<?php

namespace App\View\Widgets;

use App\Models\Bank\CouponHandout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ShopWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('validate-shop-coupons');
    }

    public function render()
    {
        return view('widgets.shop', [
            'redeemed_cards' => CouponHandout::whereDate('code_redeemed', Carbon::today())
                ->orderBy('updated_at', 'desc')
                ->count(),
        ]);
    }
}
