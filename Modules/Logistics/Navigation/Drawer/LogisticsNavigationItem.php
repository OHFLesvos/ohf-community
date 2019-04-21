<?php

namespace Modules\Logistics\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Logistics\Entities\Supplier;

use Illuminate\Support\Facades\Auth;

class LogisticsNavigationItem extends BaseNavigationItem {

    public function getRoute(): string
    {
        return route('logistics.index');
    }

    protected $caption = 'logistics::logistics.logistics';

    protected $icon = 'truck';

    protected $active = 'logistics*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Supplier::class);
    }

}
