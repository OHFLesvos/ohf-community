<?php

namespace Modules\People\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class PeopleNavigationItem extends BaseNavigationItem {

    protected $route = 'people.index';

    protected $caption = 'people::people.people';

    protected $icon = 'users';

    protected $active = 'people*';

    public function isAuthorized(): bool
    {
        return Gate::allows('manage-people');
    }

}
