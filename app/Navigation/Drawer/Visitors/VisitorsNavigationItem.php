<?php

namespace App\Navigation\Drawer\Visitors;

use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Gate;

class VisitorsNavigationItem extends BaseNavigationItem
{
    protected string $route = 'visitors.index';

    public function getCaption(): string
    {
        return __('Visitors');
    }

    protected string $icon = 'door-open';

    protected string|array $active = 'visitors*';

    public function isAuthorized(): bool
    {
        return Gate::allows('register-visitors');
    }
}
