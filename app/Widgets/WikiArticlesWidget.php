<?php

namespace App\Widgets;

use App\WikiArticle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WikiArticlesWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

    function view(): string
    {
        return 'dashboard.widgets.wiki';
    }

    function args(): array {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}