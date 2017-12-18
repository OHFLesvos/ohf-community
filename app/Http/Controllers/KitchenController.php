<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class KitchenController extends Controller
{
    private static $types = [ 'incomming', 'outgoing' ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        Validator::make($request->all(), [
            'date' => 'date'
        ])->validate();

        if (isset($request->date)) {
            $date = new Carbon($request->date);
        } else {
            $date = Carbon::today();
        }

        return view('kitchen.index', [
            'date' => $date,
            'types' => self::$types,
            'data' => collect(self::$types)->mapWithKeys(function($e){
                return [$e => Article::where('type', $e)->orderBy('name')->get()];
            })
        ]);
    }

    public function store(Request $request) {
        Validator::make($request->all(), [
            'date' => 'date',
            'type' => 'in:' . implode(',', self::$types),
        ])->validate();

        if (isset($request->date)) {
            $date = new Carbon($request->date);
        } else {
            $date = Carbon::today();
        }

        $updated = false;

        if (isset($request->value)) {
            foreach ($request->value as $k => $v) {
                if (isset($v) && is_numeric($v)) {
                    $article = Article::find($k);
                    if ($article != null) {
                        $value = $v - $article->dayTransactions($date);
                        if ($value != 0) {
                            $transaction = new Transaction();
                            $transaction->value = $value;
                            if (!$date->isToday()) {
                                $transaction->created_at = $date->endOfDay();
                            }
                            $article->transactions()->save($transaction);
                            $updated = true;
                        }
                    }
                }
            }
        }

        if (!empty($request->new_name) && !empty($request->new_value) && !empty($request->new_unit)) {
            $article = Article::where('name', $request->new_name)->where('type', $request->type)->first();
            if ($article == null) {
                $article = new Article();
                $article->name = $request->new_name;
                $article->unit = $request->new_unit;
                $article->type = $request->type;
                $article->save();
            }
            $transaction = new Transaction();
            $transaction->value = $request->new_value;
            if (!$date->isToday()) {
                $transaction->created_at = $date->endOfDay();
            }
            $article->transactions()->save($transaction);
            $updated = true;
        }

        return redirect()->route('kitchen.index')
            ->with('info', $updated ? 'Values have been updated.' : 'No changes.');
    }

    /**
     * Shows an article and statistics about it
     */
    public function showArticle(Article $article) {
        return view('kitchen.article', [
            'article' => $article,
            'date_from' =>Carbon::today()->subDays(30),
            'date_to' => Carbon::today(),
        ]);
    }

    /**
     * Parses optional date boundaries from a request
     */
    private function getDatesFromRequest(Request $request) {
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

    /**
     * Returns article transaction value per day as JSON, limited by from and to date
     */
    public function transactionsPerDay(Article $article, Request $request) {
        list($from, $to) = $this->getDatesFromRequest($request);

        // Collect values
        $date = $from;
        $data = [];
        do {
            $data[$date->format('D j. M')] = $article->dayTransactions($date);
        } while($from->addDays(1) <= $to);

        // Return JSON
        return response()->json([
            'name' => $article->name,
            'unit' => $article->unit,
            'data' => $data,
        ]);
    }

    /**
     * Returns average article transaction value per week day as JSON, limited by from and to date
     */
    public function avgTransactionsPerWeekDay(Article $article, Request $request) {
        list($from, $to) = $this->getDatesFromRequest($request);

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
            'name' => $article->name,
            'unit' => $article->unit,
            'data' => $data,
        ]);
    }
}
