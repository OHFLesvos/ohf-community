<?php

namespace Modules\Accounting\Providers;

use App\Providers\BaseNavigationServiceProvider;

class NavigationServiceProvider extends BaseNavigationServiceProvider
{
    protected $items = [
        \App\Navigation\AccountingNavigationItem::class => 7,
    ];

}
