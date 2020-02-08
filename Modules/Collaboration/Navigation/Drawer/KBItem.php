<?php

namespace Modules\Collaboration\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Collaboration\Entities\WikiArticle;

use Illuminate\Support\Facades\Auth;

class KBItem extends BaseNavigationItem {

    protected $route = 'kb.index';

    protected $caption = 'collaboration::kb.knowledge_base';

    protected $icon = 'book';

    protected $active = 'kb*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

}
