<?php

namespace Modules\Bank\Util;

use Modules\Bank\Entities\CouponHandout;
use Modules\Bank\Entities\CouponType;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class BankStatistics
{
    public static function getNumberOfPersonsServedToday() : int {
        $q = CouponHandout::whereDate('date', Carbon::today())
                ->groupBy('person_id')
                ->select('person_id');
        return DB::table(DB::raw('('.$q->toSql().') as o2'))
            ->mergeBindings($q->getQuery())
            ->count();
    }

    public static function getNumberOfCouponsHandedOut() : int {
        return (int)CouponHandout::whereDate('date', Carbon::today())
                ->select(DB::raw('sum(amount) as total'))
                ->get()
                ->first()
                ->total;
    }

    public static function getTodaysDailySpendingLimitedCoupons() {
        return CouponType::select('name', DB::raw('SUM(amount) as total'), 'daily_spending_limit')
            ->where('daily_spending_limit', '>', 0)
            ->whereDate('date', Carbon::today())
            ->join('coupon_handouts', 'coupon_handouts.coupon_type_id', '=', 'coupon_types.id')
            ->groupBy('coupon_types.id')
            ->get()
            ->mapWithKeys(function($e) {
                return [ $e->name => [ 'count' => (int)$e->total, 'limit' => $e->daily_spending_limit ] ];
            })
            ->toArray();
    }
}
