<?php

namespace App\Navigation\Drawer\Settings;

use App\Navigation\Drawer\BaseNavigationItem;

class SettingsNavigationItem extends BaseNavigationItem
{
    protected $route = 'settings.edit';

    public function getCaption(): string
    {
        return __('Settings');
    }

    protected $icon = 'cogs';

    protected $active = 'settings';

    protected $authorized = true;
}
