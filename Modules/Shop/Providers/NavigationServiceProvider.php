<?php

namespace Modules\Shop\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Shop\Navigation\Drawer\ShopNavigationItem::class => 8,
        \Modules\Shop\Navigation\Drawer\BarberNavigationItem::class => 9,
    ];

    protected $contextButtons = [
        'shop.index' => \Modules\Shop\Navigation\ContextButtons\ShopContextButtons::class,
        'shop.settings.edit' => \Modules\Shop\Navigation\ContextButtons\ShopSettingsContextButtons::class,

        'shop.barber.index' => \Modules\Shop\Navigation\ContextButtons\BarberContextButtons::class,
        'shop.barber.settings.edit' => \Modules\Shop\Navigation\ContextButtons\BarberSettingsContextButtons::class,
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
