<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class ReportingNavigationItem extends BaseNavigationItem
{
    protected $route = 'reporting.index';

    protected $caption = 'app.reporting';

    protected $icon = 'chart-bar';

    protected $active = 'reporting*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-reports');
    }
}
