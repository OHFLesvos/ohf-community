<?php

namespace App\Navigation;

use App\WikiArticle;
use Illuminate\Support\Facades\Auth;

class WikiArticlesItem extends BaseNavigationItem {

    protected $route = 'wiki.articles.index';

    protected $caption = 'wiki.wiki';

    protected $icon = 'book';

    protected $active = 'wiki/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

}
