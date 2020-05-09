<?php

namespace App\Widgets\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class KBWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', WikiArticle::class);
    }

    public function view(): string
    {
        return 'collaboration.dashboard.widgets.kb';
    }

    public function args(): array
    {
        return [
            'num_articles' => WikiArticle::count(),
            'latest_article' => WikiArticle::orderBy('updated_at', 'DESC')->first(),
        ];
    }
}
