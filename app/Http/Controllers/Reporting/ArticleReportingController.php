<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ArticleController;
use App\Article;
use App\Transaction;
use App\Project;

class ArticleReportingController extends Controller
{
    /**
     * Shows an article and statistics about it
     */
    public function articles(Project $project, Request $request) {
        return view('reporting.projects.articles', [
            'projectName' => $project->name,
            'types' => ArticleController::$types,
            'data' => collect(ArticleController::$types)->mapWithKeys(function($e) use($project) {
                return [$e => $project->articles()
                    ->where('type', $e)
                    ->orderBy('name')
                    ->get()];
            })
        ]);
    }

    /**
     * Returns article transaction value per day as JSON, limited by from and to date
     */
    public function transactionsPerDay(Article $article, Request $request) {
        list($from, $to) = self::getDatesFromRequest($request);

        // Collect values
        $date = $from;
        $data = [];
        do {
            $data[$date->format('D j. M')] = $article->dayTransactions($date);
        } while($from->addDays(1) <= $to);

        // Return JSON
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data)
            ],
        ]);
    }

    /**
     * Returns average article transaction value per week day as JSON, limited by from and to date
     */
    public function avgTransactionsPerWeekDay(Article $article, Request $request) {
        list($from, $to) = self::getDatesFromRequest($request);

        // Collect values
        $date = $from;
        $weekdays = [];
        do {
            $val = $article->dayTransactions($date);
            $weekdays[$date->format('l')][] = $val;
        } while($from->addDays(1) <= $to);

        // Calculate average over weekdays
        $data = [];
        $wdate = Carbon::today()->startOfWeek();
        do { 
            $k = $wdate->format('l');
            $v = $weekdays[$k];
            $filtered = array_filter($v);
            $count = count($filtered);
            $data[$k] = $count > 0 ? (array_sum($filtered) / $count) : null;
        } while ($wdate->addDays(1) <= Carbon::today()->endOfWeek());

        // Return JSON
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                $article->name => array_values($data)
            ],
        ]);
    }

    /**
     * Parses optional date boundaries from a request
     */
    private static function getDatesFromRequest(Request $request) {
        // Validate request data
        Validator::make($request->all(), [
            'from' => 'date',
            'to' => 'date',
        ])->validate();

        // Parse dates from request
        $from = isset($request->from) ? new Carbon($request->from) : Carbon::today()->subDays(30);
        $to = isset($request->to) ? new Carbon($request->to) : Carbon::today();

        // Return as array
        return [$from, $to];
    }
}
