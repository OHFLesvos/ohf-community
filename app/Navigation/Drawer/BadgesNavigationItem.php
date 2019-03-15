<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class BadgesNavigationItem extends BaseNavigationItem {

    protected $route = 'badges.index';

    protected $caption = 'people.badges';

    protected $icon = 'id-card';

    protected $active = 'badges*';

    public function isAuthorized(): bool
    {
        return Gate::allows('create-badges');
    }

}
