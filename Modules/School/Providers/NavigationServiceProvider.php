<?php

namespace Modules\School\Providers;

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
        \Modules\School\Navigation\Drawer\SchoolNavigationItem::class => 11,
    ];

    protected $contextButtons = [
        'school.classes.index' => \Modules\School\Navigation\ContextButtons\SchoolClassIndexContextButtons::class,
        'school.classes.create' => \Modules\School\Navigation\ContextButtons\SchoolClassCreateContextButtons::class,
        'school.classes.edit' => \Modules\School\Navigation\ContextButtons\SchoolClassEditContextButtons::class,
        'school.classes.students.index' => \Modules\School\Navigation\ContextButtons\SchoolClassStudentsIndexContextButtons::class,
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
