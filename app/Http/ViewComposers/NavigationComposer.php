<?php

namespace App\Http\ViewComposers;

use App\Person;
use App\Role;
use App\Task;
use App\User;
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
                    'caption' => 'Dashboard',
                    'icon' => 'home',
                    'active' => '/',
                    'authorized' => true
                ],
                [
                    'route' => 'people.index',
                    'caption' => 'People',
                    'icon' => 'users',
                    'active' => 'people*',
                    'authorized' => Auth::user()->can('list', Person::class)
                ],
                [
                    'route' => 'bank.index',
                    'caption' => 'Bank',
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
                    'route' => 'calendar',
                    'caption' => 'Calendar',
                    'icon' => 'calendar',
                    'active' => 'calendar*',
                    'authorized' => true // TODO,
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
                    'caption' => 'Reporting',
                    'icon' => 'bar-chart',
                    'active' => 'reporting*',
                    'authorized' => Gate::allows('view-reports'),
                ],
                [
                    'route' => 'users.index',
                    'caption' => 'Users',
                    'icon' => 'users',
                    'active' => 'users*',
                    'authorized' => Auth::user()->can('list', User::class)
                ],
                [
                    'route' => 'roles.index',
                    'caption' => 'Roles',
                    'icon' => 'tags',
                    'active' => 'roles*',
                    'authorized' => Auth::user()->can('list', Role::class)
                ]
            ];
            $view->with('nav', $nav);
        }
    }
}
