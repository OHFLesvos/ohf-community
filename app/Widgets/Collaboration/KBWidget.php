<?php

namespace App\Widgets\Collaboration;

use App\Widgets\Widget;
use App\Models\Collaboration\WikiArticle;

use Illuminate\Support\Facades\Auth;

class KBWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

    function view(): string
    {
        return 'collaboration.dashboard.widgets.kb';
    }

    function args(): array
    {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}