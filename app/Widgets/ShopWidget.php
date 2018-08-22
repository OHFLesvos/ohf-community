<?php

namespace App\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\People\Bank\WithdrawalController;
use App\CouponHandout;

class ShopWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('validate-shop-coupons');
    }

    function view(): string
    {
        return 'dashboard.widgets.shop';
    }

    function args(): array {
        $redeemed_cards = CouponHandout
            ::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->count();

        return [
            'redeemed_cards' => $redeemed_cards,
        ];
    }
}