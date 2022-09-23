<?php

namespace App\Navigation\Drawer;

class HomeNavigationItem extends BaseNavigationItem
{
    protected string $route = 'home';

    public function getCaption(): string
    {
        return __('Dashboard');
    }

    protected string $icon = 'home';

    protected string|array $active = '/';

    protected bool $authorized = true;
}
