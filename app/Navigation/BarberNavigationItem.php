<?php

namespace App\Navigation;

use Illuminate\Support\Facades\Gate;

class BarberNavigationItem extends BaseNavigationItem {

    protected $route = 'shop.barber.index';

    protected $caption = 'shop.barber_shop';

    protected $icon = 'scissors';

    protected $active = 'barber*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-barber-list');
    }

}
