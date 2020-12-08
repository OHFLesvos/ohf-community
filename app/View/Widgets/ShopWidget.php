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

    public function view(): string
    {
        return 'widgets.shop';
    }

    public function args(): array
    {
        $redeemed_cards = CouponHandout::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->count();
        return [
            'redeemed_cards' => $redeemed_cards,
        ];
    }
}
