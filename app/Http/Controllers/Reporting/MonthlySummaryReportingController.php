<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\CouponHandout;
use App\Person;

class MonthlySummaryReportingController extends BaseReportingController
{
    public function index(Request $request) {
        list($from, $to) = self::getMonthRangeDatesFromRequest($request);

        return view('reporting.monthly-summary', [
            'monthDate' => $from,
            'months' => self::monthsWithData(),
            'coupons_handed_out' => self::couponsHandedOut($from, $to),
            'coupon_types_handed_out' => self::couponTypesHandedOut($from, $to),
            'unique_visitors' => self::uniqueVisitors($from, $to),
            'total_visitors' => self::totalVisitors($from, $to),
            'days_active' => self::daysActive($from, $to),
            'new_registrations' => self::newRegistrations($from, $to),
        ]);
    }

    private static function monthsWithData() {
        $months = CouponHandout::select(DB::raw('DATE_FORMAT(date, \'%Y-%m\') as y_m'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->get()
            ->pluck('y_m');
        
        $months = $months->merge(Person::withTrashed()->select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m\') as y_m'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->pluck('y_m'));

        return $months
            ->sort()
            ->mapWithKeys(function($m){
                return [ $m => (new Carbon($m))->format('F Y') ];
            })
            ->toArray();
    }

    private static function couponsHandedOut($from, $to) {
        return CouponHandout::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->count();
    }
    
    private static function couponTypesHandedOut($from, $to) {
        return CouponHandout::select('coupon_types.name', DB::raw('COUNT(coupon_type_id) as count'))
            ->whereDate('coupon_handouts.date', '>=', $from)
            ->whereDate('coupon_handouts.date', '<=', $to)
            ->join('coupon_types', 'coupon_type_id', 'coupon_types.id')
            ->groupBy('coupon_type_id')
            ->orderBy('count', 'DESC')
            ->get()
            ->map(function($i){ return [
                'label' => $i->name,
                'value' => $i->count
            ];});
    }

    private static function uniqueVisitors($from, $to) {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('person_id')
        );
    }

    private static function totalVisitors($from, $to) {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('person_id')
                ->groupBy('date')
        );
    }

    private static function daysActive($from, $to) {
        return self::countResults(
            CouponHandout::whereDate('date', '>=', $from)
                ->whereDate('date', '<=', $to)
                ->groupBy('date')
        );
    }

    private static function newRegistrations($from, $to) {
        return Person::withTrashed()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->count();
    }

    private static function countResults($query) {
        return DB::table(DB::raw('('.$query->toSql().') as t'))
            ->mergeBindings($query->getQuery())
            ->count();
    }
}
