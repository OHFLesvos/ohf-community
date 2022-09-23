<?php

namespace App\Navigation\Drawer\Settings;

use App\Navigation\Drawer\BaseNavigationItem;

class SettingsNavigationItem extends BaseNavigationItem
{
    protected string $route = 'settings.edit';

    public function getCaption(): string
    {
        return __('Settings');
    }

    protected string $icon = 'cogs';

    protected string|array $active = 'settings';

    protected bool $authorized = true;
}
