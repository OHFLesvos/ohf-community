<?php

namespace App\Navigation\Drawer\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\Drawer\BaseNavigationItem;

class FundraisingNavigationItem extends BaseNavigationItem
{
    protected $route = 'fundraising.index';

    protected $caption = 'fundraising.donation_management';

    protected $icon = 'donate';

    protected $active = 'fundraising*';

    public function isAuthorized(): bool
    {
        return request()->user()->can('viewAny', Donor::class);
    }
}
