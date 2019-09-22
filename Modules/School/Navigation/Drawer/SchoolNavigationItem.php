<?php

namespace Modules\School\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class SchoolNavigationItem extends BaseNavigationItem {

    protected $route = 'school.classes.index';

    protected $caption = 'school::school.school';

    protected $icon = 'school';

    protected $active = 'school*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-school');
    }

}
