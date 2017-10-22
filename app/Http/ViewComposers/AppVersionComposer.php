<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class AppVersionComposer {

	/**
     * Create the composer.
     *
     * @return void
     */
    public function __construct()
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
        $view->with('app_version', Config::get('app.version'));
    }
}
