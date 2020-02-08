<?php

namespace Modules\Collaboration\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class CalendarNavigationItem extends BaseNavigationItem {

    protected $route = 'calendar';

    protected $caption = 'collaboration::calendar.calendar';

    protected $icon = 'calendar';

    protected $active = 'calendar*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-calendar');
    }

}
