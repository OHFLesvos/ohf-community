<?php

namespace Modules\Wiki\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Wiki\Entities\WikiArticle;

use Illuminate\Support\Facades\Auth;

class WikiArticlesItem extends BaseNavigationItem {

    protected $route = 'wiki.articles.index';

    protected $caption = 'wiki::wiki.wiki';

    protected $icon = 'book';

    protected $active = 'wiki/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

}
