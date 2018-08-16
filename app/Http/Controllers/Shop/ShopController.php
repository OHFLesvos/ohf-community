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
        $redeemed = null;

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

            if ($handout != null) {
                $redeemed = $handout->code_redeemed;
                if ($redeemed == null) {
                    $handout->code_redeemed = Carbon::now();
                    $handout->save();
                }
            }
                
        }

        $redeemed_cards = CouponHandout
            ::whereDate('code_redeemed', Carbon::today())
            ->where('code', '!=', $code)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('shop.index', [
            'code' => $code,
            'handout' => $handout,
            'redeemed' => $redeemed,
            'redeemed_cards' => $redeemed_cards,
        ]);
    }
}

