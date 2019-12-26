<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;

use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Search card
     */
    public function index()
    {
        return view('shop::index', [
            'redeemed_cards' => self::getTodaysRedemedCards(),
        ]);
    }

    private static function getTodaysRedemedCards()
    {
        return CouponHandout::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();
    }

}
