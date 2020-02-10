<?php

namespace App\Widgets\Shop;

use App\Widgets\Widget;

use App\Models\Bank\CouponHandout;

use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;

class ShopWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('validate-shop-coupons');
    }

    function view(): string
    {
        return 'shop.dashboard.widgets.shop';
    }

    function args(): array {
        $redeemed_cards = CouponHandout::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->count();
        return [
            'redeemed_cards' => $redeemed_cards,
        ];
    }
}