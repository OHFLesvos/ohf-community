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
        \Modules\KB\Navigation\Drawer\WikiArticlesItem::class => 6,
    ];

    protected $contextButtons = [
        'kb.articles.index' => \Modules\KB\Navigation\ContextButtons\WikiArticleIndexContextButtons::class,
        'kb.articles.latestChanges' => \Modules\KB\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'kb.articles.tags' => \Modules\KB\Navigation\ContextButtons\WikiArticleReturnToIndexContextButtons::class,
        'kb.articles.tag' => \Modules\KB\Navigation\ContextButtons\WikiArticleReturnToTagsContextButtons::class,
        'kb.articles.create' => \Modules\KB\Navigation\ContextButtons\WikiArticleCreateContextButtons::class,
        'kb.articles.show' => \Modules\KB\Navigation\ContextButtons\WikiArticleShowContextButtons::class,
        'kb.articles.edit' => \Modules\KB\Navigation\ContextButtons\WikiArticleEditContextButtons::class,
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
