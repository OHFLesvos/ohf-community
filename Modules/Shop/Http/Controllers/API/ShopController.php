<?php

namespace Modules\Shop\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;
use Modules\Shop\Transformers\ShopCard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * List cards handed out today
     */
    public function listCards()
    {
        $handout = CouponHandout::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();

        return ShopCard::collection($handout);
    }

    /**
     * Search card
     */
    public function getCard(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'alpha_num'
            ]
        ]);

        $qry = CouponHandout::query();
        if (strlen($request->code) == 7) {
            $qry->where(DB::raw("SUBSTR(code, 1, 7)"), $request->code);
        } else {
            $qry->where('code', $request->code);
        }

        $handout = $qry->orderBy('date', 'desc')
            ->with('couponType', 'person')
            ->firstOrFail();

        Log::notice('Shop: Show card.', [
            'code' => $handout->code,
            'handout' => $handout->date,
        ]);

        return new ShopCard($handout);
    }

    /**
     * Redeem card
     */
    public function redeemCard(CouponHandout $handout)
    {
        if ($error = self::checkHandoutValidity($handout)) {
            return response()
                ->json(['error' => $error], 422);
        }

        $handout->code_redeemed = Carbon::now();
        $handout->save();

        Log::notice('Shop: Redeem code.', [
            'code' => $handout->code,
        ]);

        return response()->json([
            'message' => __('shop::shop.card_redeemed')
        ]);
    }

    public function cancelCard(CouponHandout $handout)
    {
        if ($error = self::checkHandoutValidity($handout)) {
            return response()
                ->json(['error' => $error], 422);
        }

        $handout->delete();

        Log::notice('Shop: Card has been cancelled.', [
            'code' => $handout->handout,
            'handout' => $handout != null ? $handout->date : null,
        ]);

        return response()->json([
            'message' => __('shop::shop.card_has_been_cancelled')
        ]);
    }

    private static function checkHandoutValidity(CouponHandout $handout)
    {
        if ($handout->code_redeemed != null) {
            return __('shop::shop.card_already_redeemed');
        }
        if ($handout->isCodeExpired()) {
            return __('shop::shop.card_expired');
        }
        return null;
    }

    public function summary()
    {
        $data = CouponHandout::whereDate('date', '>=', Carbon::today()->subDays(7))
            ->where('code_redeemed', null)
            ->groupBy('date')
            ->select('date')
            ->selectRaw('COUNT(id) as total')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($data);
    }

    public function deleteNonRedeemed(Request $request)
    {
        $request->validate([
            'date' => [
                'required',
                'date'
            ]
        ]);

        CouponHandout::whereDate('date', $request->date)
            ->where('code_redeemed', null)
            ->delete();

        return response()->json([
            'message' => __('shop::shop.cards_removed')
        ]);
    }
}
