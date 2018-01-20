<?php

namespace App\Http\ViewComposers;

use App\Person;
use App\Role;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class ContextMenuComposer {

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
        $currentRouteName = Route::currentRouteName();
        $view->with('menu', $this->getMenu($view, $currentRouteName));
        $view->with('buttons', $this->getButtons($view, $currentRouteName));
    }

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    public function getMenu(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {
            case 'people.index':
                return [
                    [
                        'url' => route('people.export'),
                        'caption' => 'Export',
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ],
                    [
                        'url' => route('people.import'),
                        'caption' => 'Import',
                        'icon' => 'upload',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                ];
            case 'bank.withdrawal':
            case 'bank.withdrawalSearch':
                return [
                    [
                        'url' => route('bank.export'),
                        'caption' => 'Export',
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ],
                    [
                        'url' => route('bank.import'),
                        'caption' => 'Import',
                        'icon' => 'upload',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                    [
                        'url' => route('bank.maintenance'),
                        'caption' => 'Maintenance',
                        'icon' => 'eraser',
                        'authorized' => Auth::user()->can('cleanup', Person::class)
                    ],
                ];
        }
        return [];
    }

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    private function getButtons(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {
            //
            // Users
            //
            case 'users.index':
                return [
                    'action' => [
                        'url' => route('users.create'),
                        'caption' => 'Add',
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', User::class)
                    ],
                    'roles' => [
                        'url' => route('roles.index'),
                        'caption' => 'Manage Roles',
                        'icon' => 'tags',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];
            case 'users.create':
                return [
                    'back' => [
                        'url' => route('users.index'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            case 'users.show':
                $user = $view->getData()['user'];
                return [
                    'action' => [
                        'url' => route('users.edit', $user),
                        'caption' => 'Edit',
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $user)
                    ],
                    'delete' => [
                        'url' => route('users.destroy', $user),
                        'caption' => 'Delete',
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $user),
                        'confirmation' => 'Really delete this user?'
                    ],
                    'back' => [
                        'url' => route('users.index'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            case 'users.edit':
                $user = $view->getData()['user'];
                return [
                    'back' => [
                        'url' => route('users.show', $user),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $user)
                    ]
                ];
            //
            // Roles
            //
            case 'roles.index':
                return [
                    'action' => [
                        'url' => route('roles.create'),
                        'caption' => 'Add',
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Role::class)
                    ],
                    'users' => [
                        'url' => route('users.index'),
                        'caption' => 'Manage Users',
                        'icon' => 'users',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            case 'roles.create':
                return [
                    'back' => [
                        'url' => route('roles.index'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];
            case 'roles.show':
                $role = $view->getData()['role'];
                return [
                    'action' => [
                        'url' => route('roles.edit', $role),
                        'caption' => 'Edit',
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $role)
                    ],
                    'delete' => [
                        'url' => route('roles.destroy', $role),
                        'caption' => 'Delete',
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $role),
                        'confirmation' => 'Really delete this role?'
                    ],
                    'back' => [
                        'url' => route('roles.index'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];
            case 'roles.edit':
                $role = $view->getData()['role'];
                return [
                    'back' => [
                        'url' => route('roles.show', $role),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $role)
                    ]
                ];

            //
            // People
            //
            case 'people.index':
                return [
                    'report'=> [
                        'url' => route('reporting.people'),
                        'caption' => 'Report',
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-people-reports')
                    ],
                    'action' => [
                        'url' => route('people.create'),
                        'caption' => 'Register',
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ]
                ];
            case 'people.create':
                return [
                    'back' => [
                        'url' => route(session('peopleOverviewRouteName', 'people.index')),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];
            case 'people.show':
                $person = $view->getData()['person'];
                return [
                    'action' => [
                        'url' => route('people.edit', $person),
                        'caption' => 'Edit',
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $person)
                    ],
                    'delete' => [
                        'url' => route('people.destroy', $person),
                        'caption' => 'Delete',
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $person),
                        'confirmation' => 'Really delete this person?'
                    ],
                    'back' => [
                        'url' => route(session('peopleOverviewRouteName', 'people.index')),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];
            case 'people.edit':
                $person = $view->getData()['person'];
                $url = route('people.show', $person);
                if (preg_match('/bank\\/withdrawal\\/search/', url()->previous())) {
                    $url = route('bank.withdrawalSearch');
                }
                return [
                    'back' => [
                        'url' => $url,
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $person)
                    ]
                ];
            case 'people.import':
                return [
                    'back' => [
                        'url' => route('people.index'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];

            //
            // Bank
            //
            case 'bank.index':
                return [
                    'settings' => [
                        'url' => route('bank.settings'),
                        'caption' => 'Settings',
                        'icon' => 'cogs',
                        'authorized' => Gate::allows('configure-bank')
                    ],
                ];
            case 'bank.withdrawal':
            case 'bank.withdrawalSearch':
                return [
                    'action' => [
                        'url' => route('people.create'),
                        'caption' => 'Register',
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                    'transactions' => [
                        'url' => route('bank.withdrawalTransactions'),
                        'caption' => 'Transactions',
                        'icon' => 'list',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ],
                    'report'=> [
                        'url' => route('reporting.bank.withdrawals'),
                        'caption' => 'Report',
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-bank-reports')
                    ],
                    'deposit' => [
                        'url' => route('bank.deposit'),
                        'caption' => 'Deposit',
                        'icon' => 'money',
                        'authorized' => Gate::allows('do-bank-deposits')
                    ],
                    'back' => [
                        'url' => route('bank.index'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-bank-index')
                    ],
                ];
            case 'bank.deposit':
                return [
                    'report'=> [
                        'url' => route('reporting.bank.deposits'),
                        'caption' => 'Report',
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-bank-reports')
                    ],                    
                    'withdrawal' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => 'Withdrawal',
                        'icon' => 'id-card',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ],
                    'back' => [
                        'url' => route('bank.index'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-bank-index')
                    ],
                ];
            case 'bank.settings':
                return [
                    'back' => [
                        'url' => route('bank.index'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-bank-index')
                    ]
                ];
            case 'bank.withdrawalTransactions':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];
            case 'bank.maintenance':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];
            case 'bank.import':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];

            //
            // Logistics
            //
            case 'logistics.articles.index':
                $project = $view->getData()['project'];
                return [
                    'report'=> [
                        'url' => route('reporting.articles', $project),
                        'caption' => 'Report',
                        'icon' => 'line-chart',
                        'authorized' => true // TODO
                    ],
                    'back' => [
                        'url' => route('logistics.index'),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('use-logistics')
                    ]
                ];
            case 'logistics.articles.edit':
                $article = $view->getData()['article'];
                return [
                    'delete' => [
                        'url' => route('logistics.articles.destroyArticle', $article),
                        'caption' => 'Delete',
                        'icon' => 'trash',
                        'authorized' => Gate::allows('use-logistics'),
                        'confirmation' => 'Really delete this article?'
                    ],
                    'back' => [
                        'url' => route('logistics.articles.index', $article->project),
                        'caption' => 'Cancel',
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('use-logistics')
                    ]
                ];
            
            //
            // Reporting
            //
            case 'reporting.people':
            case 'reporting.bank.withdrawals':
            case 'reporting.bank.deposits':
                return [
                    'back' => [
                        'url' => url()->previous(),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
            case 'reporting.articles':
                if (!preg_match('#/reporting/#', url()->previous())) {
                    session(['articleReportingBackUrl' => url()->previous()]);
                }
                return [
                    'back' => [
                        'url' => session('articleReportingBackUrl', route('reporting.index')),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
            case 'reporting.article':
                $article = $view->getData()['article'];
                return [
                    'back' => [
                        'url' => route('reporting.articles', $article->project),
                        'caption' => 'Close',
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
        }
        return [];
    }
}
