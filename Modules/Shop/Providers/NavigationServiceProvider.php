<?php

namespace Modules\Shop\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Shop\Navigation\Drawer\ShopNavigationItem::class => 8,
    ];

    protected $contextButtons = [
        'shop.index' => \Modules\Shop\Navigation\ContextButtons\ShopContextButtons::class,
        'shop.settings.edit' => \Modules\Shop\Navigation\ContextButtons\ShopSettingsContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextButtons();
    }

}
