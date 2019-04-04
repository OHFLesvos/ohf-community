<?php

namespace Modules\Bank\Util;

use App\CouponHandout;

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
}
