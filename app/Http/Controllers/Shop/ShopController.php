<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CouponHandout;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function index() {
        return view('shop.index');
    }

    public function searchCode(string $code) {
        // code_redeemed
        $handout = CouponHandout::where('code', $code)->orderBy('date', 'asc')->first();

        if ($handout != null) {
            $redeemed = $handout->code_redeemed;
            if ($redeemed != null) {
                $handout->code_redeemed = Carbon::now();
                $handout->save();
            }
        }

        return view('shop.searchCode', [
            'handout' => $handout,
            'redeemed' => $redeemed,
        ]);
    }
}

