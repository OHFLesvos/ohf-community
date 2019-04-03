<?php

namespace Modules\Library\Providers;

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
        \Modules\Library\Navigation\Drawer\LibraryNavigationItem::class => 10,
    ];

    protected $contextButtons = [
        'library.lending.index' => \Modules\Library\Navigation\ContextButtons\LibraryLendingIndexContextButtons::class,
        'library.settings.edit' => \Modules\Library\Navigation\ContextButtons\LibrarySettingsContextButtons::class,
        'library.lending.persons' => \Modules\Library\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.books' => \Modules\Library\Navigation\ContextButtons\LibraryReturnToIndexContextButtons::class,
        'library.lending.person' => \Modules\Library\Navigation\ContextButtons\LibraryLendingPersonContextButtons::class,
        'library.lending.personLog' => \Modules\Library\Navigation\ContextButtons\LibraryLendingPersonLogContextButtons::class,
        'library.lending.book' => \Modules\Library\Navigation\ContextButtons\LibraryLendingBookContextButtons::class,
        'library.lending.bookLog' => \Modules\Library\Navigation\ContextButtons\LibraryLendingBookLogContextButtons::class,
        'library.books.index' => \Modules\Library\Navigation\ContextButtons\LibraryBookIndexContextButtons::class,
        'library.books.create' => \Modules\Library\Navigation\ContextButtons\LibraryBookCreateContextButtons::class,
        'library.books.edit' => \Modules\Library\Navigation\ContextButtons\LibraryBookEditContextButtons::class,
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
