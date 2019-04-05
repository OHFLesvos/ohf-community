<?php

namespace Modules\Bank\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class BankNavigationItem extends BaseNavigationItem {

    protected $route = 'bank.index';

    protected $caption = 'bank::bank.bank';

    protected $icon = 'bank';

    protected $active = 'bank*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-bank-index');
    }

}
