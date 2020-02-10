<?php

namespace App\Navigation\Drawer\Helpers;

use App\Navigation\Drawer\BaseNavigationItem;

use App\Models\Helpers\Helper;

use Illuminate\Support\Facades\Auth;

class HelpersNavigationItem extends BaseNavigationItem {

    protected $route = 'people.helpers.index';

    protected $caption = 'helpers.helpers';

    protected $icon = 'id-badge';

    protected $active = 'helpers*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

}
