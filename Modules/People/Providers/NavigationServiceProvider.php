<?php

namespace Modules\People\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\People\Navigation\Drawer\PeopleNavigationItem::class => 1,
    ];

    protected $contextMenus = [
        'people.index' => \Modules\People\Navigation\ContextMenu\PeopleContextMenu::class,
    ];

    protected $contextButtons = [
        'people.index' => \Modules\People\Navigation\ContextButtons\PeopleIndexContextButtons::class,
        'people.create' => \Modules\People\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'people.show' => \Modules\People\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'people.relations' => \Modules\People\Navigation\ContextButtons\PeopleRelationsContextButtons::class,
        'people.edit' => \Modules\People\Navigation\ContextButtons\PeopleEditContextButtons::class,
        'people.duplicates' => \Modules\People\Navigation\ContextButtons\PeopleDuplicatesContextButtons::class,
        'people.import' => \Modules\People\Navigation\ContextButtons\PeopleImportContextButtons::class,
        
        'reporting.monthly-summary' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.visitors' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.withdrawals' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.deposits' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.privacy' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
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
