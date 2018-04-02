<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SelectDateRange;
use App\CouponType;
use App\CouponHandout;
use App\Project;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankReportingController extends BaseReportingController
{
    /**
     * View for withdtawal statistics
     * 
     * @return \Illuminate\Http\Response
     */
    function withdrawals() {
        $coupons = CouponType
            ::orderBy('order')
            ->orderBy('name')
            ->get()
            ->map(function($coupon){
                return [
                    'coupon' => $coupon,
                    'avg_sum' => self::getAvgTransactionSumPerDay($coupon),
                    'highest_sum' => self::getHighestSumPerDay($coupon),
                    'last_month_sum' => self::sumOfTransactions($coupon, Carbon::today()->subMonth()->startOfMonth(), Carbon::today()->subMonth()->endOfMonth()),
                    'this_month_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()),
                    'last_week_sum' => self::sumOfTransactions($coupon, Carbon::today()->subWeek()->startOfWeek(), Carbon::today()->subWeek()->endOfWeek()),
                    'this_week_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()),
                    'today_sum' => self::sumOfTransactions($coupon, Carbon::today()->startOfDay(), Carbon::today()->endOfDay()),
                ];
            });
        return view('reporting.bank.withdrawals', [
            'coupons' => $coupons,
            'from' => Carbon::today()->subMonth()->toDateString(),
            'to' => Carbon::today()->toDateString(),
        ]);
    }

    private static function getAvgTransactionSumPerDay(CouponType $coupon) {
        $sub = CouponHandout
            ::select(DB::raw('sum(amount) as sum'))
            ->where('coupon_type_id', $coupon->id)
            ->groupBy('date');
        $result = DB
            ::table( DB::raw("({$sub->toSql()}) as sub") )
            ->select(DB::raw('round(avg(sum), 1) as avg'))
            ->mergeBindings($sub->getQuery())
            ->first();
        return $result != null ? $result->avg : null;
    }

    private static function getHighestSumPerDay(CouponType $coupon) {
        return CouponHandout
                ::select(DB::raw('sum(amount) as sum, date'))
                ->where('coupon_type_id', $coupon->id)
                ->groupBy('date')
                ->orderBy('sum', 'DESC')
                ->limit(1)
                ->first();
    }

    private static function sumOfTransactions(CouponType $coupon, Carbon $from, Carbon $to) {
        $result = CouponHandout
            ::whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $to->toDateString())
            ->where('coupon_type_id', $coupon->id)
            ->select(DB::raw('sum(amount) as sum'))
            ->first();
        return $result != null ? $result->sum : null;
    }

    /**
     * Returns chart data for number of coupons handed out per day.
     * 
     * @param  \App\CouponType $coupon the coupon type
     * @param  \App\Http\Requests\SelectDateRange  $request
     * @return \Illuminate\Http\Response
     */
    public function couponsHandedOutPerDay(CouponType $coupon, SelectDateRange $request) {
        $from = new Carbon($request->from);
        $to = new Carbon($request->to);
        $q = self::createDateCollectionEmpty($from, $to)
            ->merge(
                CouponHandout
                    ::where('coupon_type_id', $coupon->id)
                    ->whereDate('date', '>=', $from->toDateString())
                    ->whereDate('date', '<=', $to->toDateString())
                    ->groupBy('date')
                    ->select(DB::raw('SUM(amount) as sum'), 'date')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->date => $item->sum];
                    })
            )
            ->reverse()
            ->toArray();        
        return response()->json([
            'labels' => array_keys($q),
            'datasets' => [
                'Value' => array_values($q),
            ]
        ]);
    }

    /**
     * View for deposit statistics
     */
    function deposits() {
        return view('reporting.bank.deposits', [
            'projects' => Project::orderBy('name')
                ->where('enable_in_bank', true)
                ->get(),
        ]);
    }

    /**
     * Deposit statistics over all projects
     */
    public function depositStats() {
        $days = 30;

        // Labels
        $lables = [];
        for ($i = $days; $i >= 0; $i--) {
            $lables[] = Carbon::today()->subDays($i)->format('D j. M');
        }
        $datasets = [];

        // Projects
        $projects = Project::orderBy('name')
            ->where('enable_in_bank', true)
            ->get();
        foreach ($projects as $project) {
            $transactions = [];
            for ($i = $days; $i >= 0; $i--) { 
                $transactions[] = $project->dayTransactions(Carbon::today()->subDays($i));
            }
            $datasets[$project->name] = $transactions;
        }

        return response()->json([
            'labels' => $lables,
            'datasets' => $datasets,
        ]);
    }

    /**
     * Deposit statistics per project
     */
    public function projectDepositStats(Project $project) {
        $days = 30;

        // Labels
        $lables = [];
        for ($i = $days; $i >= 0; $i--) {
            $lables[] = Carbon::today()->subDays($i)->format('D j. M');
        }

        // Projects
        $datasets = [];
        $transactions = [];
        for ($i = $days; $i >= 0; $i--) { 
            $transactions[] = $project->dayTransactions(Carbon::today()->subDays($i));
        }
        $datasets[$project->name] = $transactions;

        return response()->json([
            'labels' => $lables,
            'datasets' => $datasets,
        ]);
    }
}
