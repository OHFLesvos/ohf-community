<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ArticleController;
use App\Article;
use App\Transaction;
use App\Project;
use Illuminate\Support\Facades\DB;

class ArticleReportingController extends BaseReportingController
{
    /**
     * Shows all articles of a project and statistics about them
     */
    public function articles(Project $project, Request $request) {
        return view('reporting.projects.articles', [
            'projectName' => $project->name,
            'types' => ArticleController::$types,
            'data' => collect(ArticleController::$types)->mapWithKeys(function($e) use($project) {
                return [$e => $project->articles()
                    ->where('type', $e)
                    ->orderBy('name')
                    ->get()
                    ->map(function($a){
                        $daily = array_values(self::getTransactionsPerDay($a, Carbon::now()->subDay()->startOfDay(), Carbon::now()));
                        $weekly = array_values(self::getTransactionsPerWeek($a, Carbon::now()->subWeek()->startOfWeek(), Carbon::now()));
                        $monthly = array_values(self::getTransactionsPerMonth($a, Carbon::now()->subMonth()->startOfMonth(), Carbon::now()));
                        return [
                            'article' => $a,
                            'today' => $daily[1],
                            'yesterday' => $daily[0],
                            'this_week' => $weekly[1],
                            'last_week' => $weekly[0],
                            'this_month' => $monthly[1],
                            'last_month' => $monthly[0],
                        ];
                    })];
            })
        ]);
    }

    /**
     * Shows an article and statistics about it
     */
    public function article(Article $article) {
        return view('reporting.projects.article', [
            'article' => $article,
        ]);
    }

    /**
     * Returns article transaction value per day as JSON, limited by from and to date
     */
    public function transactionsPerDay(Article $article, Request $request) {
        //list($from, $to) = self::getDatesFromRequest($request);
        $from = Carbon::now()->subMonth(1);
        $to = Carbon::now();        
        
        $data = self::getTransactionsPerDay($article, $from, $to);

        // Return JSON
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data)
            ],
        ]);
    }

    private static function getTransactionsPerDay(Article $article, $from, $to) {
        return self::createDateCollectionEmpty($from, $to)
            ->merge(
                self::getTransactionsPerDayQuery($article, $from, $to)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->sum];
                }))
            ->reverse()
            ->toArray();
    }

    /**
     * Returns article transaction value per week as JSON, limited by from and to date
     */
    public function transactionsPerWeek(Article $article, Request $request) {
        $from = Carbon::now()->subMonth(6)->startOfWeek();
        $to = Carbon::now();
        
        $data = self::getTransactionsPerWeek($article, $from, $to);

        // Return JSON
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data)
            ],
        ]);
    }

    private static function getTransactionsPerWeek(Article $article, $from, $to) {
        $sql = self::getTransactionsPerDayQuery($article, $from, $to);
        return self::createWeekCollectionEmpty($from, $to)
            ->merge(                
                // MySQL week number formats: https://www.w3resource.com/mysql/date-and-time-functions/mysql-week-function.php
                DB::table(DB::raw('('.$sql->toSql().') as o2'))
                    ->select(DB::raw('CONCAT(LPAD(WEEK(date, 3), 2, 0), \' / \', YEAR(date)) as week'), DB::raw('SUM(sum) as sum'))
                    ->groupBy(DB::raw('WEEK(date, 3)'), DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($sql)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->week => $item->sum];
                    })
            )
            ->reverse()
            ->toArray();
    }    

    /**
     * Returns article transaction value per month as JSON, limited by from and to date
     */
    public function transactionsPerMonth(Article $article, Request $request) {
        $from = Carbon::now()->subMonth(6)->startOfWeek();
        $to = Carbon::now();
        
        $data = self::getTransactionsPerMonth($article, $from, $to);

        // Return JSON
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data)
            ],
        ]);
    }

    private static function getTransactionsPerMonth(Article $article, $from, $to) {
        $sql = self::getTransactionsPerDayQuery($article, $from, $to);
        return self::createMonthCollectionEmpty($from, $to)
            ->merge(                
                DB::table(DB::raw('('.$sql->toSql().') as o2'))
                    ->select(DB::raw('DATE_FORMAT(date, \'%M %Y\') as month'), DB::raw('SUM(sum) as sum'))
                    ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($sql)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->month => $item->sum];
                    })
            )
            ->reverse()
            ->toArray();
    }

    /**
     * Returns average article transaction value per week day as JSON, limited by from and to date
     */
    public function avgTransactionsPerWeekDay(Article $article, Request $request) {
        //list($from, $to) = self::getDatesFromRequest($request);
        $from = Carbon::now()->subMonth(3)->startOfWeek();
        $to = Carbon::now();
        $data = self::getAvgTransactionsPerWeekDay($article, $from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data),
            ]
        ]);
    }

    private static function getAvgTransactionsPerWeekDay(Article $article, $from, $to) {
        $query = self::getTransactionsPerDayQuery($article, $from, $to);
        return self::createDayOfWeekCollectionEmpty()
            ->merge(
                // MySQL day name and day of week formats: 
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayname-function.php
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayofweek-function.php
                DB::table(DB::raw('('.$query->toSql().') as o2'))
                    ->select(DB::raw('DAYNAME(date) as day'), DB::raw('AVG(sum) as avg'))
                    ->groupBy(DB::raw('DAYOFWEEK(date)'))
                    ->orderBy(DB::raw('DAYOFWEEK(date)'))
                    ->mergeBindings($query)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->day => round($item->avg, 1)];
                    })
            )
            ->toArray();
    }

    private static function getTransactionsPerDayQuery(Article $article, $from, $to) {
        return $article->transactions()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(value) as sum'))
            ->groupBy(DB::raw('day(created_at)'), DB::raw('month(created_at)'), DB::raw('year(created_at)'))
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at')
            ->getBaseQuery();
    }
}
