<?php

namespace App\Navigation\Drawer\Fundraising;

use App\Navigation\Drawer\BaseNavigationItem;

class FundraisingNavigationItem extends BaseNavigationItem
{
    protected string $route = 'fundraising.index';

    public function getCaption(): string
    {
        return __('Donation Management');
    }

    protected string $icon = 'donate';

    protected string|array $active = 'fundraising*';

    public function isAuthorized(): bool
    {
        return request()->user()->can('view-fundraising');
    }
}
