<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Transaction;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public static $types = [ 'incomming', 'outgoing' ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project, Request $request) {
        Validator::make($request->all(), [
            'date' => 'date'
        ])->validate();

        if (isset($request->date)) {
            $date = new Carbon($request->date);
        } else {
            $date = Carbon::today();
        }

        return view('logistics.articles.index', [
            'project' => $project,
            'date' => $date,
            'types' => self::$types,
            'data' => collect(self::$types)->mapWithKeys(function($e) use($project) {
                return [$e => $project->articles()
                    ->where('type', $e)
                    ->orderBy('name')
                    ->get()];
            })
        ]);
    }

    public function store(Project $project, Request $request) {
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

        // Update existing articles
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

        // Create new article
        if (!empty($request->new_name) && !empty($request->new_value) && !empty($request->new_unit)) {
            foreach ($request->new_name as $k => $v) {
                if (!empty($v) && !empty($request->new_unit[$k])) {

                    $article = Article::where('name', $v)
                        ->where('type', $k)
                        ->first();
                    if ($article == null) {
                        $article = new Article();
                        $article->name = $v;
                        $article->unit = $request->new_unit[$k];
                        $article->type = $k;
                        $project->articles()->save($article);
                    }

                    $value = $request->new_value[$k];
                    if (!empty($value)) {
                        $transaction = new Transaction();
                        $transaction->value = $value;
                        if (!$date->isToday()) {
                            $transaction->created_at = $date->endOfDay();
                        }
                        $article->transactions()->save($transaction);
                    }

                    $updated = true;
                }
            }
        }

        return redirect()->route('logistics.articles.index', $project)
            ->with($updated ? 'success' : 'info', $updated ? 'Values have been updated.' : 'No changes.');
    }

    /**
     * Edits an article
     */
    public function edit(Article $article) {
        return view('logistics.articles.edit', [
            'article' => $article,
            'types' => collect(self::$types)->mapWithKeys(function($t){
                return [ $t => ucfirst($t) ];
            }),
        ]);
    }

    /**
     * Updates an article
     */
    public function update(Article $article, Request $request) {
        $article->name = $request->name;
        $article->type = $request->type;
        $article->unit = $request->unit;
        $updated = $article->isDirty();
        $article->save();
        return redirect()->route('logistics.articles.index', $article->project)
            ->with('info', $updated ? 'Article has been updated.' : 'No changes.');
    }

    /**
     * Deletes an article
     */
    public function destroyArticle(Article $article) {
        $article->delete();
        // TODO delete transactions
        return redirect()->route('logistics.articles.index', $article->project)
            ->with('info', 'Article has been deleted.');
    }
}
