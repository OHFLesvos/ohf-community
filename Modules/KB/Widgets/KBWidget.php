<?php

namespace Modules\KB\Widgets;

use App\Widgets\Widget;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Support\Facades\Auth;

class KBWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

    function view(): string
    {
        return 'kb::dashboard.widgets.kb';
    }

    function args(): array {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}