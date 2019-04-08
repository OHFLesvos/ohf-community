<?php

namespace Modules\Wiki\Providers;

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
        \Modules\Wiki\Navigation\Drawer\WikiArticlesItem::class => 6,
    ];

    protected $contextButtons = [
        'wiki.articles.index' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleIndexContextButtons::class,
        'wiki.articles.tag' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'wiki.articles.latestChanges' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'wiki.articles.create' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleCreateContextButtons::class,
        'wiki.articles.show' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleShowContextButtons::class,
        'wiki.articles.edit' => \Modules\Wiki\Navigation\ContextButtons\WikiArticleEditContextButtons::class,
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
