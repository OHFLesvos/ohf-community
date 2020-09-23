<?php

namespace App\Navigation\Drawer\Visitors;

use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Gate;

class VisitorsNavigationItem extends BaseNavigationItem
{
    protected $route = 'visitors.index';

    protected $caption = 'visitors.visitors';

    protected $icon = 'door-open';

    protected $active = 'visitors*';

    public function isAuthorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
