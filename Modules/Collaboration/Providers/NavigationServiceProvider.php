<?php

namespace Modules\Collaboration\Providers;

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
        \Modules\Collaboration\Navigation\Drawer\KBItem::class => 6,
        \Modules\Collaboration\Navigation\Drawer\CalendarNavigationItem::class => 11,
        \Modules\Collaboration\Navigation\Drawer\TasksNavigationItem::class => 12,
    ];

    protected $contextButtons = [
        'kb.index'                  => \Modules\Collaboration\Navigation\ContextButtons\IndexContextButtons::class,
        'kb.latestChanges'          => \Modules\Collaboration\Navigation\ContextButtons\LatestChangesContextButtons::class,
        'kb.tags'                   => \Modules\Collaboration\Navigation\ContextButtons\TagsContextButtons::class,
        'kb.tag'                    => \Modules\Collaboration\Navigation\ContextButtons\TagContextButtons::class,
        'kb.articles.index'         => \Modules\Collaboration\Navigation\ContextButtons\ArticleIndexContextButtons::class,
        'kb.articles.create'        => \Modules\Collaboration\Navigation\ContextButtons\ArticleCreateContextButtons::class,
        'kb.articles.show'          => \Modules\Collaboration\Navigation\ContextButtons\ArticleShowContextButtons::class,
        'kb.articles.edit'          => \Modules\Collaboration\Navigation\ContextButtons\ArticleEditContextButtons::class,
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
