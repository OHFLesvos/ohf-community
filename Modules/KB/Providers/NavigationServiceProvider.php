<?php

namespace Modules\KB\Providers;

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
        \Modules\KB\Navigation\Drawer\KBItem::class => 6,
    ];

    protected $contextButtons = [
        'kb.index'                  => \Modules\KB\Navigation\ContextButtons\IndexContextButtons::class,
        'kb.latestChanges'          => \Modules\KB\Navigation\ContextButtons\LatestChangesContextButtons::class,
        'kb.tags'                   => \Modules\KB\Navigation\ContextButtons\TagsContextButtons::class,
        'kb.tag'                    => \Modules\KB\Navigation\ContextButtons\TagContextButtons::class,
        'kb.articles.index'         => \Modules\KB\Navigation\ContextButtons\ArticleIndexContextButtons::class,
        'kb.articles.create'        => \Modules\KB\Navigation\ContextButtons\ArticleCreateContextButtons::class,
        'kb.articles.show'          => \Modules\KB\Navigation\ContextButtons\ArticleShowContextButtons::class,
        'kb.articles.edit'          => \Modules\KB\Navigation\ContextButtons\ArticleEditContextButtons::class,
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
