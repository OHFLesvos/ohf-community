<?php

namespace App\Navigation\Drawer;

use App\Helper;
use Illuminate\Support\Facades\Auth;

class HelpersNavigationItem extends BaseNavigationItem {

    protected $route = 'people.helpers.index';

    protected $caption = 'people.helpers';

    protected $icon = 'id-badge';

    protected $active = 'helpers*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

}
