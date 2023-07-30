<?php

namespace App\Http\ViewComposers;

use App\Support\Facades\NavigationItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $view->with('nav', NavigationItems::items()
                ->filter(fn ($item) => $item->isAuthorized()));
        }
    }
}
