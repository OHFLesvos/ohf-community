<?php

namespace Modules\Helpers\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Helpers\Entities\Helper;

use Illuminate\Support\Facades\Auth;

class HelpersNavigationItem extends BaseNavigationItem {

    protected $route = 'people.helpers.index';

    protected $caption = 'helpers::helpers.helpers';

    protected $icon = 'id-badge';

    protected $active = 'helpers*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

}
