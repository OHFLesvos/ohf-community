<?php

namespace App\Http\ViewComposers;

use App\Support\Facades\ContextButtons;
use App\Support\Facades\ContextMenus;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class ContextMenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $currentRouteName = Route::currentRouteName();

        $view->with('menu', ContextMenus::get($currentRouteName));
        $view->with('buttons', ContextButtons::get($currentRouteName, $view));
    }
}
