<?php

namespace Modules\Accounting\Providers;

use App\Providers\BaseDashboardWidgetsProvider;

class DashboardWidgetsProvider extends BaseDashboardWidgetsProvider
{
    protected $widgets = [
        \Modules\Accounting\Widgets\TransactionsWidget::class => 7,
    ];

}
