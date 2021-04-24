<?php

namespace App\Navigation\Drawer\Fundraising;

use App\Navigation\Drawer\BaseNavigationItem;

class FundraisingNavigationItem extends BaseNavigationItem
{
    protected $route = 'fundraising.index';

    public function getCaption(): string
    {
        return __('app.donation_management');
    }

    protected $icon = 'donate';

    protected $active = 'fundraising*';

    public function isAuthorized(): bool
    {
        return request()->user()->can('view-fundraising');
    }
}
