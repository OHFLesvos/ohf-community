<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class ReportsNavigationItem extends BaseNavigationItem
{
    protected $route = 'reports.index';

    public function getCaption(): string
    {
        return __('app.reports');
    }

    protected $icon = 'chart-bar';

    protected $active = 'reports*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-reports');
    }
}
