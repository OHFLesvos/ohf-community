<?php

namespace App\Navigation;

use Illuminate\Support\Facades\Gate;

class ReportingNavigationItem extends BaseNavigationItem {

    protected $route = 'reporting.index';

    protected $caption = 'app.reporting';

    protected $icon = 'bar-chart';

    protected $active = 'reporting*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-reports');
    }

}
