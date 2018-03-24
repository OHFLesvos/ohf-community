<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;
use App\CouponHandout;
use App\Project;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankReportingController extends Controller
{
    /**
     * View for withdtawal statistics
     */
    function withdrawals() {
        return view('reporting.bank.withdrawals', [
            'avg_sum' => self::getAvgTransactionSumPerDay(),
            'highest_sum' => CouponHandout
                ::select(DB::raw('sum(amount) as sum, date'))
                ->groupBy('date')
                ->orderBy('sum', 'DESC')
                ->limit(1)
                ->first(),
            'last_month_sum' => self::sumOfTransactions(Carbon::today()->subMonth()->startOfMonth(), Carbon::today()->subMonth()->endOfMonth()),
            'this_month_sum' => self::sumOfTransactions(Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()),
            'last_week_sum' => self::sumOfTransactions(Carbon::today()->subWeek()->startOfWeek(), Carbon::today()->subWeek()->endOfWeek()),
            'this_week_sum' => self::sumOfTransactions(Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()),
            'today_sum' => self::sumOfTransactions(Carbon::today()->startOfDay(), Carbon::today()->endOfDay()),
        ]);
    }

    private static function getAvgTransactionSumPerDay() {
        $sub = CouponHandout
            ::select(DB::raw('sum(amount) as sum'))
            ->groupBy('date');
        $result = DB
            ::table( DB::raw("({$sub->toSql()}) as sub") )
            ->select(DB::raw('round(avg(sum), 1) as avg'))
            ->mergeBindings($sub->getQuery())
            ->first();
        return $result != null ? $result->avg : null;
    }

    private static function sumOfTransactions($from, $to) {
        $result = CouponHandout
            ::whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $to->toDateString())
            ->select(DB::raw('sum(amount) as sum'))
            ->first();
        return $result != null ? $result->sum : null;
    }

    /**
     * Number of transactions per day
     */
    public function numTransactions() {
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $q = Transaction
                ::whereDate('created_at', '=', $day->toDateString())
				->where('transactionable_type', 'App\Person')
                ->select('value')
                ->get();
            $data['labels'][] = $day->toDateString();
            $data['datasets']['Transactions'][] = collect($q)
                ->count();
        }
		return response()->json($data);
    }

    /**
     * Sum of transactions per day
     */
    public function sumTransactions() {
        $data = [];
        for ($i = 30; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $q = Transaction
                ::whereDate('created_at', '=', $day->toDateString())
				->where('transactionable_type', 'App\Person')
                ->select('value')
                ->get();
            $data['labels'][] = $day->toDateString();
            $data['datasets']['Value'][] = collect($q)
                ->map(function($item){
                    return $item->value;
                })->sum();
        }
		return response()->json($data);
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
