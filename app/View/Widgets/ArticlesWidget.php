<?php

namespace App\View\Widgets;

use App\Models\Collaboration\WikiArticle;
use Illuminate\Support\Facades\Auth;

class ArticlesWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', WikiArticle::class);
    }

    public function view(): string
    {
        return 'widgets.articles';
    }

    public function args(): array
    {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}
