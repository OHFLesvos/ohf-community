<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class AppVersionComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $releaseFile = base_path('RELEASE');
        if (is_file($releaseFile)) {
            $version = trim(file_get_contents($releaseFile));
            $view->with('app_version', $version);
        }
    }
}
