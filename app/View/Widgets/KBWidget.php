<?php

namespace App\View\Widgets;

use App\Models\Collaboration\WikiArticle;
use Illuminate\Support\Facades\Auth;

class KBWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', WikiArticle::class);
    }

    public function render()
    {
        return view('widgets.kb',  [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('created_at', 'DESC')->first(),
        ]);
    }
}
