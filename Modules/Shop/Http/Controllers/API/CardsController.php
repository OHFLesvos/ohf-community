<?php

namespace Modules\Shop\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;
use Modules\Bank\Entities\CouponType;

use Modules\Shop\ConfigurationException;
use Modules\Shop\Transformers\ShopCard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use anlutro\LaravelSettings\Facade as Setting;

use Carbon\Carbon;

class CardsController extends Controller
{
    /**
     * List cards which have been redeemed today
     */
    public function listRedeemedToday()
    {
        try {
            $handout = self::getCouponType()->couponHandouts()
                ->whereDate('code_redeemed', Carbon::today())
                ->orderBy('updated_at', 'desc')
                ->get();

            return ShopCard::collection($handout);
        } catch (ConfigurationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search card by code
     */
    public function searchByCode(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'alpha_num'
            ]
        ]);

        try {
            $handout = self::getCouponType()->couponHandouts()
                ->withCode($request->code)
                ->orderBy('date', 'desc')
                ->with('couponType', 'person')
                ->firstOrFail();

            return new ShopCard($handout);
        } catch (ConfigurationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redeem card
     */
    public function redeem(CouponHandout $handout)
    {
        try {
            self::validateChangeAllowed($handout);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
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

    /**
     * Cancel card
     */
    public function cancel(CouponHandout $handout)
    {
        try {
            self::validateChangeAllowed($handout);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
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

    /**
     * List non-redeemed cards grouped by day
     */
    public function listNonRedeemedByDay()
    {
        try {
            $couponType = self::getCouponType();
            $data = $couponType->couponHandouts()
                ->whereDate('date', '>=', Carbon::today()->subDays(7))
                ->where('code_redeemed', null)
                ->groupBy('date')
                ->select('date')
                ->selectRaw('COUNT(id) as total')
                ->orderBy('date', 'desc')
                ->get()
                ->map(function($e) use($couponType) {
                    $acceptDate = Carbon::today()->subDays($couponType->code_expiry_days - 1);
                    $e['expired'] = $acceptDate->gt(new Carbon($e->date));
                    return $e;
                });

            return response()->json($data);
        } catch (ConfigurationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete non-redeemed cards by the given date
     */
    public function deleteNonRedeemedByDay(Request $request)
    {
        $request->validate([
            'date' => [
                'required',
                'date'
            ]
        ]);

        try {
            self::getCouponType()->couponHandouts()
                ->whereDate('date', $request->date)
                ->where('code_redeemed', null)
                ->delete();

            return response()->json([
                'message' => __('shop::shop.cards_removed')
            ]);
        } catch (ConfigurationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gets the configured coupon type or throws an exception
     * if not defined or if defined type does not exist.
     */
    private static function getCouponType()
    {
        if (!Setting::has('shop.coupon_type')) {
            throw new ConfigurationException(__('shop::shop.coupon_type_not_configured_yet'));
        }
        $couponType = CouponType::find(Setting::get('shop.coupon_type'));
        if ($couponType == null) {
            throw new ConfigurationException(__('shop::shop.configured_coupon_type_does_not_exist'));
        }
        return $couponType;
    }

    /**
     * Validate if changing a coupon is allowed (not redeemed, not expired)
     */
    private static function validateChangeAllowed(CouponHandout $handout)
    {
        if ($handout->code_redeemed != null) {
            throw new \Exception(__('shop::shop.card_already_redeemed'));
        }
        if ($handout->isCodeExpired()) {
            throw new \Exception(__('shop::shop.card_expired'));
        }
    }
}
