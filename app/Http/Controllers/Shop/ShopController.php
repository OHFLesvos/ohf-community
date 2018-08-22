<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    const COUPON_VALID_DAYS = 2;

    public function index(Request $request) {

        $code = $request->code;
        $handout = null;
        $expired = false;

        if ($code != null) {

            // code_redeemed
            $acceptDate = Carbon::today()->subDays(self::COUPON_VALID_DAYS - 1);
            $handout = CouponHandout::where('code', $code)
                ->whereDate('date', '>=', $acceptDate)
                ->orderBy('date', 'desc')
                ->first();
            if ($handout == null) {
                $handout = CouponHandout::where('code', $code)
                    ->whereNull('code_redeemed')
                    ->orderBy('date', 'desc')
                    ->first();
                $expired = true;
            }

            Log::notice('Shop: Search code.', [
                'code' => $code,
                'handout' => $handout != null ? $handout->date : null,
            ]);
        }

        $redeemed_cards = CouponHandout
            ::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('shop.index', [
            'code' => $code,
            'handout' => $handout,
            'redeemed_cards' => $redeemed_cards,
            'expired' => $expired,
        ]);
    }

    public function redeem(Request $request) {

        // code_redeemed
        $acceptDate = Carbon::today()->subDays(self::COUPON_VALID_DAYS - 1);
        $handout = CouponHandout::where('code', $request->code)
            ->whereDate('date', '>=', $acceptDate)
            ->orderBy('date', 'desc')
            ->first();
        if ($handout == null) {
            $handout = CouponHandout::where('code', $request->code)
                ->whereNull('code_redeemed')
                ->orderBy('date', 'desc')
                ->first();
        }

        if ($handout != null) {
            $redeemed = $handout->code_redeemed;
            if ($redeemed == null) {
                $handout->code_redeemed = Carbon::now();
                $handout->save();

                Log::notice('Shop: Redeem code.', [
                    'code' => $request->code,
                ]);

                return redirect()->route('shop.index')
                    ->with('success', __('shop.card_redeemed'));
            }
        }

        return redirect()->route('shop.index');
    }
}

