<?php

namespace Modules\Helpers\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegisterContextMenus;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Helpers\Navigation\Drawer\HelpersNavigationItem::class => 3,
    ];

    protected $contextMenus = [
        'people.helpers.index' => \Modules\Helpers\Navigation\ContextMenu\HelpersContextMenu::class,
    ];

    protected $contextButtons = [
        'people.helpers.index' => \Modules\Helpers\Navigation\ContextButtons\HelperIndexContextButtons::class,
        'people.helpers.show' => \Modules\Helpers\Navigation\ContextButtons\HelperShowContextButtons::class,
        'people.helpers.edit' => \Modules\Helpers\Navigation\ContextButtons\HelpersEditContextButtons::class,
        'people.helpers.create' => \Modules\Helpers\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.createFrom' => \Modules\Helpers\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.import' => \Modules\Helpers\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.export' => \Modules\Helpers\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
        'people.helpers.report' => \Modules\Helpers\Navigation\ContextButtons\HelpersReturnToIndexContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextMenus();
        $this->registerContextButtons();
    }

}
