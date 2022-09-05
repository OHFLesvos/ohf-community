<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use TJVB\LaravelGitHash\Contracts\GitHashLoader;

class AppVersionComposer
{
    public function __construct(private GitHashLoader $gitHashLoader)
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('app_version', $this->gitHashLoader->getGitHash()->short());
    }
}
