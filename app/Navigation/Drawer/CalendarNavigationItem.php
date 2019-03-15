<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class CalendarNavigationItem extends BaseNavigationItem {

    protected $route = 'calendar';

    protected $caption = 'calendar.calendar';

    protected $icon = 'calendar';

    protected $active = 'calendar*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-calendar');
    }

}
