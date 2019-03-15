<?php

namespace App\Http\ViewComposers;

use App\Person;
use App\Helper;
use App\Role;
use App\Task;
use App\User;
use App\Donor;
use App\Donation;
use App\CouponType;
use App\WikiArticle;
use App\Support\Facades\ContextMenus;
use App\Support\Facades\ContextButtons;

use App\InventoryItemTransaction;
use App\InventoryStorage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ContextMenuComposer {

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
