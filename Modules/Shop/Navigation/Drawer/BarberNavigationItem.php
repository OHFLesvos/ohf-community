<?php

namespace Modules\Shop\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class BarberNavigationItem extends BaseNavigationItem {

    protected $route = 'shop.barber.index';

    protected $caption = 'shop::shop.barber_shop';

    protected $icon = 'scissors';

    protected $active = 'barber*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-barber-list');
    }

}
