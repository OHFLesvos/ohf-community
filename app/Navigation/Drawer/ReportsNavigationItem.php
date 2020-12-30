<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class ReportsNavigationItem extends BaseNavigationItem
{
    protected $route = 'reports.index';

    protected $caption = 'app.reports';

    protected $icon = 'chart-bar';

    protected $active = 'reports*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-reports');
    }
}
