<?php

namespace App\Navigation\Drawer\People;

use App\Navigation\Drawer\BaseNavigationItem;

use App\Models\People\Person;

use Illuminate\Support\Facades\Auth;

class PeopleNavigationItem extends BaseNavigationItem {

    protected $route = 'people.index';

    protected $caption = 'people.people';

    protected $icon = 'users';

    protected $active = 'people*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Person::class);
    }

}
