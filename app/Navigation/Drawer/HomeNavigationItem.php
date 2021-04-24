<?php

namespace App\Navigation\Drawer;

class HomeNavigationItem extends BaseNavigationItem
{
    protected $route = 'home';

    public function getCaption(): string
    {
        return __('app.dashboard');
    }

    protected $icon = 'home';

    protected $active = '/';

    protected $authorized = true;
}
