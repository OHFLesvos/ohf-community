<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Transaction;
use Carbon\Carbon;

class KitchenController extends Controller
{
    public function index() {
        $articles = Article::orderBy('name')
            ->get()
            ->map(function($a){
                return $a->name;
            });
        return view('kitchen.index', [
            'articles' => Article::orderBy('name')->get(),
            'articleNames' => $articles,
        ]);
    }

    public function storeIncomming(Request $request) {
        $article = Article::where('name', $request->name)->first();
        if ($article == null) {
            $article = new Article();
            $article->name = $request->name;
        }
        $article->save();

        $transaction = new Transaction();
        $transaction->value = $request->value;
		$date = new Carbon($request->date);
		if (!$date->isToday()) {
			$transaction->created_at = $date->endOfDay();
		}
        $article->transactions()->save($transaction);

        return redirect()->route('kitchen.index')
            ->with('info', 'Registered ' . $transaction->value . ' \'' . $article->name . '\'.');

    }
}
