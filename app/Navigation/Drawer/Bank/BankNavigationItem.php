<?php

namespace App\Navigation\Drawer\Bank;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class BankNavigationItem extends BaseNavigationItem {

    protected $route = 'bank.index';

    protected $caption = 'bank.bank';

    protected $icon = 'university';

    protected $active = 'bank*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-bank-index');
    }

}
