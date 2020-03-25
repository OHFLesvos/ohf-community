<?php

namespace App\Http\ViewComposers;

use App\Support\Facades\NavigationItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $view->with('nav', NavigationItems::items());
        }
    }
}
