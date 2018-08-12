<?php

namespace App\Http\ViewComposers;

use App\Person;
use App\Role;
use App\Task;
use App\User;
use App\Donor;
use App\WikiArticle;
use App\MoneyTransaction;
use App\InventoryStorage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NavigationComposer {

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
        if (Auth::check()) {
            $num_open_tasks = Auth::user()->tasks()->open()->count();
            $nav = [
                [
                    'route' => 'home',
                    'caption' => __('app.dashboard'),
                    'icon' => 'home',
                    'active' => '/',
                    'authorized' => true
                ],
                [
                    'route' => 'people.index',
                    'caption' => __('people.people'),
                    'icon' => 'users',
                    'active' => 'people*',
                    'authorized' => Auth::user()->can('list', Person::class)
                ],
                [
                    'route' => 'bank.index',
                    'caption' => __('people.bank'),
                    'icon' => 'bank',
                    'active' => 'bank*',
                    'authorized' => Gate::allows('view-bank-index')
                ],
                [
                    'route' => 'logistics.index',
                    'caption' => 'Logistics',
                    'icon' => 'spoon',
                    'active' => 'logistics*',
                    'authorized' => Gate::allows('use-logistics')
                ],
                [
                    'route' => 'fundraising.donors.index',
                    'caption' => __('fundraising.donation_management'),
                    'icon' => 'handshake-o',
                    'active' => 'fundraising/*',
                    'authorized' => Auth::user()->can('list', Donor::class),
                ],
                [
                    'route' => 'wiki.articles.index',
                    'caption' => __('wiki.wiki'),
                    'icon' => 'book',
                    'active' => 'wiki/*',
                    'authorized' => Auth::user()->can('list', WikiArticle::class),
                ],
                [
                    'route' => !Auth::user()->can('list', MoneyTransaction::class) && Gate::allows('view-accounting-summary') ? 'accounting.transactions.summary' : 'accounting.transactions.index',
                    'caption' => __('accounting.accounting'),
                    'icon' => 'money',
                    'active' => 'accounting/*',
                    'authorized' => Auth::user()->can('list', MoneyTransaction::class) || Gate::allows('view-accounting-summary'),
                ],
                [
                    'route' => 'inventory.storages.index',
                    'caption' => __('inventory.inventory_management'),
                    'icon' => 'archive',
                    'active' => 'inventory/*',
                    'authorized' => Auth::user()->can('list', InventoryStorage::class),
                ],
                [
                    'route' => 'shop.index',
                    'caption' => __('shop.shop'),
                    'icon' => 'shopping-bag',
                    'active' => 'shop/*',
                    'authorized' => true,
                ],
                [
                    'route' => 'calendar',
                    'caption' => 'Calendar',
                    'icon' => 'calendar',
                    'active' => 'calendar*',
                    'authorized' => Gate::allows('view-calendar'),
                ],                
                [
                    'route' => 'tasks',
                    'caption' => 'Tasks',
                    'icon' => 'tasks',
                    'active' => 'tasks*',
                    'authorized' => Auth::user()->can('list', Task::class),
                    'badge' => $num_open_tasks > 0 ? $num_open_tasks : null
                ],
                [
                    'route' => 'reporting.index',
                    'caption' => __('app.reporting'),
                    'icon' => 'bar-chart',
                    'active' => 'reporting*',
                    'authorized' => Gate::allows('view-reports'),
                ],
                [
                    'route' => 'users.index',
                    'caption' => __('app.users_and_roles'),
                    'icon' => 'users',
                    'active' => ['admin/users*', 'admin/roles*'],
                    'authorized' => Auth::user()->can('list', User::class) || Auth::user()->can('list', Role::class)
                ],
                [
                    'route' => 'logviewer.index',
                    'caption' => __('app.logviewer'),
                    'icon' => 'file-text-o',
                    'active' => 'logviewer*',
                    'authorized' => Gate::allows('view-logs'),
                ],
            ];
            $view->with('nav', $nav);
        }
    }
}
