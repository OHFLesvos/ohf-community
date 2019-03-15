<?php

namespace App\Navigation\Drawer;

use App\Donor;
use Illuminate\Support\Facades\Auth;

class FundraisingNavigationItem extends BaseNavigationItem {

    protected $route = 'fundraising.donors.index';

    protected $caption = 'fundraising.donation_management';

    protected $icon = 'handshake-o';

    protected $active = 'fundraising/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Donor::class);
    }

}
