<?php

namespace App\Navigation\Drawer\Fundraising;

use App\Models\Fundraising\Donor;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class FundraisingNavigationItem extends BaseNavigationItem
{
    protected $route = 'fundraising.donors.index';

    protected $caption = 'fundraising.donation_management';

    protected $icon = 'handshake';

    protected $active = 'fundraising/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Donor::class);
    }
}
