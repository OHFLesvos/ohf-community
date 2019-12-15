<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Search card
     */
    public function index(Request $request)
    {
        // Validate code (if given)
        $request->validate([
            'code' => [
                'nullable',
                'alpha_num'
            ]
        ]);

        $handout = null;
        $expired = false;

        if ($request->code != null) {

            // Check for shortcode
            if (strlen($request->code) == 7) {
                $handout = CouponHandout::where(DB::raw("SUBSTR(code, 1, 7)"), $request->code)->first();
                if ($handout != null) {
                    return redirect()->route('shop.index', ['code' => $handout->code]);
                }
            }

            // Search handout
            $handout = CouponHandout::where('code', $request->code)
                ->orderBy('date', 'desc')
                ->with('couponType')
                ->first();

            if ($handout != null) {
                Log::notice('Shop: Show card.', [
                    'code' => $handout->code,
                    'handout' => $handout->date,
                ]);
            }

            // Check expiry
            if ($handout != null && $handout->isCodeExpired()) {
                $expired = true;
                Log::notice('Shop: Card expired.', [
                    'code' => $handout->code,
                    'handout' => $handout->date,
                ]);
            }

        }

        return view('shop::index', [
            'code' => $request->code,
            'handout' => $handout,
            'expired' => $expired,
            'redeemed_cards' => self::getTodaysRedemedCards(),
        ]);
    }

    private static function getTodaysRedemedCards()
    {
        return CouponHandout::whereDate('code_redeemed', Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Redeem card
     */
    public function redeem(CouponHandout $handout)
    {
        if ($error = self::checkHandoutValidity($handout)) {
            return redirect()->route('shop.index')
                ->with('error', $error);
        }

        $handout->code_redeemed = Carbon::now();
        $handout->save();

        Log::notice('Shop: Redeem code.', [
            'code' => $handout->code,
        ]);

        return redirect()->route('shop.index')
            ->with('success', __('shop::shop.card_redeemed'));
    }

    /**
     * Cancel card
     */
    public function cancelCard(CouponHandout $handout)
    {
        if ($error = self::checkHandoutValidity($handout)) {
            return redirect()->route('shop.index')
                ->with('error', $error);
        }

        $handout->delete();

        Log::notice('Shop: Card has been cancelled.', [
            'code' => $handout->handout,
            'handout' => $handout != null ? $handout->date : null,
        ]);

        return redirect()->route('shop.index')
            ->with('success', __('shop::shop.card_has_been_cancelled'));
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

}
