<?php

namespace App\Navigation\Drawer\Badges;

use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Gate;

class BadgesNavigationItem extends BaseNavigationItem
{
    protected $route = 'badges.index';

    public function getCaption(): string
    {
        return __('Badges');
    }

    protected $icon = 'id-card';

    protected $active = 'badges*';

    public function isAuthorized(): bool
    {
        return Gate::allows('create-badges');
    }
}
