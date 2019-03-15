<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class BankNavigationItem extends BaseNavigationItem {

    protected $route = 'bank.index';

    protected $caption = 'people.bank';

    protected $icon = 'bank';

    protected $active = 'bank*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-bank-index');
    }

}
