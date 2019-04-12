<?php

namespace Modules\KB\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Support\Facades\Auth;

class WikiArticlesItem extends BaseNavigationItem {

    protected $route = 'kb.articles.index';

    protected $caption = 'kb::wiki.wiki';

    protected $icon = 'book';

    protected $active = 'wiki/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

}
