<?php

namespace Modules\People\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\People\Entities\Person;

use Illuminate\Support\Facades\Auth;

class PeopleNavigationItem extends BaseNavigationItem {

    protected $route = 'people.index';

    protected $caption = 'people::people.people';

    protected $icon = 'users';

    protected $active = 'people*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Person::class);
    }

}
