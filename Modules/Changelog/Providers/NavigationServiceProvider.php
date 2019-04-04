<?php

namespace Modules\Changelog\Providers;

use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegisterContextButtons;

    protected $contextButtons = [
        'changelog' => \Modules\Changelog\Navigation\ContextButtons\ChangelogContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerContextButtons();
    }

}
