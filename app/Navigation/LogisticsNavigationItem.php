<?php

namespace App\Navigation;

use Illuminate\Support\Facades\Gate;

class LogisticsNavigationItem extends BaseNavigationItem {

    protected $route = 'logistics.index';

    protected $caption = 'logistics.logistics';

    protected $icon = 'spoon';

    protected $active = 'logistics*';

    public function isAuthorized(): bool
    {
        return Gate::allows('use-logistics');
    }

}
