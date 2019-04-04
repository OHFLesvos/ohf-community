<?php

namespace App\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class => 0,
        \App\Navigation\Drawer\PeopleNavigationItem::class => 1,
        \App\Navigation\Drawer\ReportingNavigationItem::class => 14,
    ];

    protected $contextMenus = [
        'people.index' => \App\Navigation\ContextMenu\PeopleContextMenu::class,
    ];

    protected $contextButtons = [
        'people.index' => \App\Navigation\ContextButtons\PeopleIndexContextButtons::class,
        'people.create' => \App\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'people.show' => \App\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'people.relations' => \App\Navigation\ContextButtons\PeopleRelationsContextButtons::class,
        'people.edit' => \App\Navigation\ContextButtons\PeopleEditContextButtons::class,
        'people.duplicates' => \App\Navigation\ContextButtons\PeopleDuplicatesContextButtons::class,
        'people.import' => \App\Navigation\ContextButtons\PeopleImportContextButtons::class,
        
        'reporting.monthly-summary' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
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
