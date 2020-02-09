<?php

namespace App\Util\Bank;

use App\Models\Bank\CouponHandout;
use App\Models\Bank\CouponType;

use Illuminate\Support\Facades\DB;

class BankStatisticsProvider
{
    private $date;

    public function __construct($date = null)
    {
        $this->date = $date ?? today();
    }

    public function getNumberOfPersonsServed() : int {
        $q = CouponHandout::whereDate('date', $this->date)
            ->groupBy('person_id')
            ->select('person_id');
        return DB::table(DB::raw('('.$q->toSql().') as o2'))
            ->mergeBindings($q->getQuery())
            ->count();
    }

    public function getNumberOfCouponsHandedOut() : int {
        return (int)CouponHandout::whereDate('date', $this->date)
            ->selectRaw('sum(amount) as total')
            ->get()
            ->first()
            ->total;
    }

    public function getCouponsWithSpendingLimit() : \Illuminate\Support\Collection {
        return CouponType::select('name', 'daily_spending_limit')
            ->selectRaw('SUM(amount) as total')
            ->where('daily_spending_limit', '>', 0)
            ->whereDate('date', $this->date)
            ->join('coupon_handouts', 'coupon_handouts.coupon_type_id', '=', 'coupon_types.id')
            ->groupBy('coupon_types.id')
            ->get()
            ->mapWithKeys(function($e) {
                return [ $e->name => [
                    'count' => (int)$e->total,
                    'limit' => $e->daily_spending_limit
                ] ];
            });
    }
}
