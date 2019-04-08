<?php

namespace Modules\Wiki\Widgets;

use App\Widgets\Widget;

use Modules\Wiki\Entities\WikiArticle;

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
        return 'wiki::dashboard.widgets.wiki';
    }

    function args(): array {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}