<?php

namespace App\Navigation;

use Illuminate\Support\Facades\Gate;

class PeopleNavigationItem extends BaseNavigationItem {

    protected $route = 'people.index';

    protected $caption = 'people.people';

    protected $icon = 'users';

    protected $active = 'people*';

    public function isAuthorized(): bool
    {
        return Gate::allows('manage-people');
    }

}
