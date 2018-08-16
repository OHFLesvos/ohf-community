<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function index(Request $request) {

        $code = $request->code;
        $handout = null;

        if ($code != null) {

            // code_redeemed
            $handout = CouponHandout::where('code', $code)
                ->whereDate('date', Carbon::today())
                ->first();
            if ($handout == null) {
                $handout = CouponHandout::where('code', $code)
                    ->whereNull('code_redeemed')
                    ->orderBy('date', 'desc')
                    ->first();
            }
        }

        $redeemed_cards = CouponHandout
            ::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('shop.index', [
            'code' => $code,
            'handout' => $handout,
            'redeemed_cards' => $redeemed_cards,
        ]);
    }

    public function redeem(Request $request) {

        // code_redeemed
        $handout = CouponHandout::where('code', $request->code)
            ->whereDate('date', Carbon::today())
            ->first();
        if ($handout == null) {
            $handout = CouponHandout::where('code', $code)
                ->whereNull('code_redeemed')
                ->orderBy('date', 'desc')
                ->first();
        }

        if ($handout != null) {
            $redeemed = $handout->code_redeemed;
            if ($redeemed == null) {
                $handout->code_redeemed = Carbon::now();
                $handout->save();
                return redirect()->route('shop.index')
                    ->with('success', __('shop.code_redeemed'));
            }
        }

        return redirect()->route('shop.index');
    }
}

