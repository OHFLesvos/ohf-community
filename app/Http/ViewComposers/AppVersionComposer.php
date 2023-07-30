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
     */
    public function compose(View $view): void
    {
        $view->with('app_version', $this->gitHashLoader->getGitHash()->short());
    }
}
