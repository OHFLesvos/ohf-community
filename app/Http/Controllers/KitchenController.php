<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class KitchenController extends Controller
{
    public function index(Request $request) {
        Validator::make($request->all(), [
            'date' => 'date',
        ])->validate();

        if (isset($request->date)) {
            $date = new Carbon($request->date);
        } else {
            $date = Carbon::today();
        }
        $articles = Article::orderBy('name')
            ->get()
            ->map(function($a){
                return $a->name;
            });
        return view('kitchen.index', [
            'date' => $date,
            'articles' => Article::orderBy('name')->get(),
            'articleNames' => $articles,
        ]);
    }

    public function storeIncomming(Request $request) {
        Validator::make($request->all(), [
            'date' => 'date',
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

        if (!empty($request->new_name) && !empty($request->new_value)) {
            $article = Article::where('name', $request->new_name)->first();
            if ($article == null) {
                $article = new Article();
                $article->name = $request->new_name;
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
}
