<?php

namespace Modules\KB\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Support\Facades\Auth;

class KBItem extends BaseNavigationItem {

    protected $route = 'kb.index';

    protected $caption = 'kb::kb.knowledge_base';

    protected $icon = 'book';

    protected $active = 'kb*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', WikiArticle::class);
    }

}
