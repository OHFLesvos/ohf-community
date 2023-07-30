<?php

namespace App\Navigation\Drawer\Badges;

use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Gate;

class BadgesNavigationItem extends BaseNavigationItem
{
    protected string $route = 'badges.index';

    public function getCaption(): string
    {
        return __('Badges');
    }

    protected string $icon = 'id-card';

    protected string|array $active = 'badges*';

    public function isAuthorized(): bool
    {
        return Gate::allows('create-badges');
    }
}
