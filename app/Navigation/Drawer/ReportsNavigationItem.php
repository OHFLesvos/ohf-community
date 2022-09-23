<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class ReportsNavigationItem extends BaseNavigationItem
{
    protected string $route = 'reports.index';

    public function getCaption(): string
    {
        return __('Reports');
    }

    protected string $icon = 'chart-bar';

    protected string|array $active = 'reports*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-reports');
    }
}
