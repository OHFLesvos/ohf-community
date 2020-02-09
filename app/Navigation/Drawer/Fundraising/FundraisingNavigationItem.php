<?php

namespace App\Navigation\Drawer\Fundraising;

use App\Navigation\Drawer\BaseNavigationItem;

use App\Models\Fundraising\Donor;

use Illuminate\Support\Facades\Auth;

class FundraisingNavigationItem extends BaseNavigationItem {

    protected $route = 'fundraising.donors.index';

    protected $caption = 'fundraising.donation_management';

    protected $icon = 'handshake';

    protected $active = 'fundraising/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Donor::class);
    }

}
