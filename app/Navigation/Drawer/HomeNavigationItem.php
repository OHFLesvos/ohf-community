<?php

namespace App\Navigation\Drawer;

class HomeNavigationItem extends BaseNavigationItem
{
    protected $route = 'home';

    protected $caption = 'app.dashboard';

    protected $icon = 'home';

    protected $active = '/';

    protected $authorized = true;
}
