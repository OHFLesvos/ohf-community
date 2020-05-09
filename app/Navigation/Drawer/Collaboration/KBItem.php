<?php

namespace App\Navigation\Drawer\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class KBItem extends BaseNavigationItem
{
    protected $route = 'kb.index';

    protected $caption = 'kb.knowledge_base';

    protected $icon = 'book';

    protected $active = 'kb*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', WikiArticle::class);
    }
}
