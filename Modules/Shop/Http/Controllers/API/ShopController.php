<?php

namespace Modules\Shop\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;

use Modules\Shop\Http\Resources\ShopCardResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ShopController extends Controller
{
    // API: Search card
    public function searchCard(Request $request) {
        $code = $request->code;
        if ($code != null) {
            $handout = null;
            $expired = false;
   
            $acceptDate = Carbon::today()->subDays(self::getCouponValidity() - 1);
            $handout = CouponHandout
                ::where(function($q) use ($code) {
                    return $q->where('code', $code)
                        ->orWhere('code', DB::raw("SHA2('". $code ."', 256)"));
                })
                ->whereDate('date', '>=', $acceptDate)
                ->orderBy('date', 'desc')
                ->first();
            if ($handout == null) {
                $handout = CouponHandout
                    ::where(function($q) use ($code) {
                        return $q->where('code', $code)
                            ->orWhere('code', DB::raw("SHA2('". $code ."', 256)"));
                    })
                    ->whereNull('code_redeemed')
                    ->orderBy('date', 'desc')
                    ->first();
                $expired = true;
            }

            if ($handout != null) {
                return (new ShopCardResource($handout))->additional(['meta' => [
                    'key' => 'value',
                ]]);
            }
        }
        return response()->json([], 404);
    }

    // API: Redeem card
    public function redeemCard() {
        // TODO
    }
}
