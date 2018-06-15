<?php

namespace App\Http\ViewComposers;

use App\Person;
use App\Role;
use App\Task;
use App\User;
use App\Donor;
use App\Donation;
use App\CouponType;
use App\WikiArticle;
use App\MoneyTransaction;
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
                        'url' => route('people.duplicates'),
                        'caption' => __('people.find_duplicates'),
                        'icon' => 'exchange',
                        'authorized' => Auth::user()->can('cleanup', Person::class)
                    ],                    
                    [
                        'url' => route('people.import'),
                        'caption' => __('app.import'),
                        'icon' => 'upload',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                ];
            case 'bank.withdrawal':
            case 'bank.withdrawalSearch':
                return [
                    [
                        'url' => route('bank.export'),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('export', Person::class)
                    ],
                    [
                        'url' => route('bank.import'),
                        'caption' => __('app.import'),
                        'icon' => 'upload',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                    [
                        'url' => route('bank.maintenance'),
                        'caption' => __('app.maintenance'),
                        'icon' => 'eraser',
                        'authorized' => Auth::user()->can('cleanup', Person::class)
                    ],
                    [
                        'url' => route('coupons.index'),
                        'caption' => __('people.coupons'),
                        'icon' => 'ticket',
                        'authorized' => Gate::allows('configure-bank')
                    ],
                    [
                        'url' => route('bank.settings'),
                        'caption' => __('app.settings'),
                        'icon' => 'cogs',
                        'authorized' => Gate::allows('configure-bank')
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
            case 'changelog':
                return [
                    'back' => [
                        'url' => url()->previous(),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
            case 'userprofile.view2FA':
                return [
                    'back' => [
                        'url' => route('userprofile'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => true,
                    ]
                ];

            //
            // Users
            //
            case 'users.index':
                return [
                    'action' => [
                        'url' => route('users.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', User::class)
                    ],
                    'permissions' => [
                        'url' => route('users.permissions'),
                        'caption' => __('app.permissions'),
                        'icon' => 'key',
                        'authorized' => Gate::allows('view-usermgmt-reports')
                    ],
                    'privacy' => [
                        'url' => route('reporting.privacy'),
                        'caption' => __('reporting.privacy'),
                        'icon' => 'eye',
                        'authorized' => Gate::allows('view-usermgmt-reports')
                    ],                    
                ];
            case 'users.create':
                return [
                    'back' => [
                        'url' => route('users.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            case 'users.show':
                $user = $view->getData()['user'];
                return [
                    'action' => [
                        'url' => route('users.edit', $user),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $user)
                    ],
                    'delete' => [
                        'url' => route('users.destroy', $user),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $user),
                        'confirmation' => __('app.confirm_delete_user')
                    ],
                    'back' => [
                        'url' => route('users.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            case 'users.edit':
                $user = $view->getData()['user'];
                return [
                    'back' => [
                        'url' => route('users.show', $user),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $user)
                    ]
                ];
            case 'users.permissions':
                return [
                    'back' => [
                        'url' => url()->previous(),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', User::class)
                    ]
                ];
            //
            // Roles
            //
            case 'roles.index':
                return [
                    'action' => [
                        'url' => route('roles.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Role::class)
                    ],
                    'permissions' => [
                        'url' => route('roles.permissions'),
                        'caption' => __('app.permissions'),
                        'icon' => 'key',
                        'authorized' => Gate::allows('view-usermgmt-reports')
                    ]
                ];
            case 'roles.create':
                return [
                    'back' => [
                        'url' => route('roles.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];
            case 'roles.show':
                $role = $view->getData()['role'];
                return [
                    'action' => [
                        'url' => route('roles.edit', $role),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $role)
                    ],
                    'delete' => [
                        'url' => route('roles.destroy', $role),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $role),
                        'confirmation' => __('app.confirm_delete_role')
                    ],
                    'back' => [
                        'url' => route('roles.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];
            case 'roles.edit':
                $role = $view->getData()['role'];
                return [
                    'back' => [
                        'url' => route('roles.show', $role),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $role)
                    ]
                ];
            case 'roles.permissions':
                return [
                    'back' => [
                        'url' => url()->previous(),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Role::class)
                    ]
                ];

            //
            // People
            //
            case 'people.index':
                return [
                    'action' => [
                        'url' => route('people.create'),
                        'caption' => __('app.register'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                    'report'=> [
                        'url' => route('reporting.people'),
                        'caption' => __('app.report'),
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-people-reports')
                    ],
                    'export' => [
                        'url' => route('people.export'),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('export', Person::class)
                    ],
                ];
            case 'people.create':
                return [
                    'back' => [
                        'url' => route(session('peopleOverviewRouteName', 'people.index')),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];
            case 'people.show':
                $person = $view->getData()['person'];
                return [
                    'action' => [
                        'url' => route('people.edit', $person),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $person)
                    ],
                    'relations' => [
                        'url' => route('people.relations', $person),
                        'caption' => __('people.relationships'),
                        'icon' => 'users',
                        'authorized' => Auth::user()->can('update', $person)
                    ],
                    'delete' => [
                        'url' => route('people.destroy', $person),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $person),
                        'confirmation' => 'Really delete this person?'
                    ],
                    'back' => [
                        'url' => route(session('peopleOverviewRouteName', 'people.index')),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];
            case 'people.relations':
                $person = $view->getData()['person'];
                return [
                    'back' => [
                        'url' => route('people.show', $person),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $person)
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
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $person)
                    ]
                ];
            case 'people.duplicates':
                return [
                    'back' => [
                        'url' => route('people.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];
            case 'people.import':
                return [
                    'back' => [
                        'url' => route('people.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class)
                    ]
                ];

            //
            // Bank
            //
            case 'bank.withdrawal':
            case 'bank.withdrawalSearch':
            case 'bank.showCard':
                return [
                    'action' => [
                        'url' => route('people.create'),
                        'caption' => __('app.register'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Person::class)
                    ],
                    'transactions' => [
                        'url' => route('bank.withdrawalTransactions'),
                        'caption' => __('app.transactions'),
                        'icon' => 'list',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ],
                    'codecard' => [
                        'url' => route('bank.prepareCodeCard'),
                        'caption' => __('people.code_cards'),
                        'icon' => 'qrcode',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ],
                    'report'=> [
                        'url' => route('reporting.bank.withdrawals'),
                        'caption' => __('app.report'),
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-bank-reports')
                    ]
                ];
            case 'bank.deposit':
                return [
                    'transactions' => [
                        'url' => route('bank.depositTransactions'),
                        'caption' => __('app.transactions'),
                        'icon' => 'list',
                        'authorized' => Gate::allows('do-bank-deposits')
                    ],                    
                    'report'=> [
                        'url' => route('reporting.bank.deposits'),
                        'caption' => __('app.report'),
                        'icon' => 'line-chart',
                        'authorized' => Gate::allows('view-bank-reports')
                    ],                    
                ];
            case 'bank.prepareCodeCard':
                return [
                    'back' => [
                        'url' => route('bank.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-bank-index')
                    ]
                ];
            case 'bank.settings':
                return [
                    'back' => [
                        'url' => route('bank.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-bank-index')
                    ]
                ];
            case 'bank.withdrawalTransactions':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];
            case 'bank.depositTransactions':
                return [
                    'back' => [
                        'url' => route('bank.deposit'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-deposits')
                    ]
                ];
            case 'bank.maintenance':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];
            case 'bank.import':
            case 'bank.export':
                return [
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];

            case 'coupons.index':
                return [
                    'action' => [
                        'url' => route('coupons.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', CouponType::class)
                    ],
                    'back' => [
                        'url' => route('bank.withdrawal'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('do-bank-withdrawals')
                    ]
                ];
            case 'coupons.create':
                return [
                    'back' => [
                        'url' => route('coupons.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', CouponType::class)
                    ]
                ];
            case 'coupons.show':
                $coupon = $view->getData()['coupon'];
                return [
                    'action' => [
                        'url' => route('coupons.edit', $coupon),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $coupon)
                    ],
                    'delete' => [
                        'url' => route('coupons.destroy', $coupon),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $coupon),
                        'confirmation' => __('people.confirm_delete_coupon')
                    ],
                    'back' => [
                        'url' => route('coupons.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', CouponType::class)
                    ]
                ];
            case 'coupons.edit':
                $coupon = $view->getData()['coupon'];
                return [
                    'back' => [
                        'url' => route('coupons.show', $coupon),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $coupon)
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
                        'caption' => __('app.report'),
                        'icon' => 'line-chart',
                        'authorized' => true // TODO
                    ],
                    'back' => [
                        'url' => route('logistics.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('use-logistics')
                    ]
                ];
            case 'logistics.articles.edit':
                $article = $view->getData()['article'];
                return [
                    'delete' => [
                        'url' => route('logistics.articles.destroyArticle', $article),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Gate::allows('use-logistics'),
                        'confirmation' => 'Really delete this article?'
                    ],
                    'back' => [
                        'url' => route('logistics.articles.index', $article->project),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('use-logistics')
                    ]
                ];

            //
            // Donations : Donors
            //
            case 'fundraising.donors.index':
                return [
                    'action' => [
                        'url' => route('fundraising.donors.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Donor::class)
                    ],
                    'export' => [
                        'url' => route('fundraising.donors.export'),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', Donor::class)
                    ]
                ];
            case 'fundraising.donors.create':
                return [
                    'back' => [
                        'url' => route('fundraising.donors.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('create', Donor::class)
                    ]
                ];
            case 'fundraising.donors.show':
                $donor = $view->getData()['donor'];
                return [
                    'action' => [
                        'url' => route('fundraising.donors.edit', $donor),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $donor)
                    ],
                    'export' => [
                        'url' => route('fundraising.donations.export', $donor),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', Donation::class) && $donor->donations()->count() > 0
                    ],
                    'vcard' => [
                        'url' => route('fundraising.donors.vcard', $donor),
                        'caption' => __('app.vcard'),
                        'icon' => 'vcard',
                        'authorized' => Auth::user()->can('view', $donor)
                    ],
                    'delete' => [
                        'url' => route('fundraising.donors.destroy', $donor),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $donor),
                        'confirmation' => __('fundraising.confirm_delete_donor')
                    ],
                    'back' => [
                        'url' => route('fundraising.donors.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Donor::class)
                    ]
                ];
            case 'fundraising.donors.edit':
                $donor = $view->getData()['donor'];
                return [
                    'back' => [
                        'url' => route('fundraising.donors.show', $donor),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $donor)
                    ]
                ];
            case 'fundraising.donations.create':
                $donor = $view->getData()['donor'];
                return [
                    'back' => [
                        'url' => route('fundraising.donors.show', $donor),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $donor)
                    ]
                ];
            case 'fundraising.donations.edit':
                $donor = $view->getData()['donor'];
                $donation = $view->getData()['donation'];
                return [
                    'delete' => [
                        'url' => route('fundraising.donations.destroy', [$donor, $donation]),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $donation),
                        'confirmation' => __('fundraising.confirm_delete_donation')
                    ],
                    'back' => [
                        'url' => route('fundraising.donors.show', $donor),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $donor)
                    ]
                ];

            //
            // Wiki: Articles
            //
            case 'wiki.articles.index':
                return [
                    'action' => [
                        'url' => route('wiki.articles.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', WikiArticle::class)
                    ],
                    'latestChanges' => [
                        'url' => route('wiki.articles.latestChanges'),
                        'caption' => __('app.latest_changes'),
                        'icon' => 'history',
                        'authorized' => Auth::user()->can('list', WikiArticle::class)
                    ],
                ];
            case 'wiki.articles.tag':
            case 'wiki.articles.latestChanges':
                return [
                    'back' => [
                        'url' => route('wiki.articles.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', WikiArticle::class)
                    ]
                ];
            case 'wiki.articles.create':
                return [
                    'back' => [
                        'url' => route('wiki.articles.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', WikiArticle::class)
                    ]
                ];
            case 'wiki.articles.show':
                $article = $view->getData()['article'];
                return [
                    'action' => [
                        'url' => route('wiki.articles.edit', $article),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $article)
                    ],
                    'delete' => [
                        'url' => route('wiki.articles.destroy', $article),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $article),
                        'confirmation' => __('wiki.confirm_delete_article')
                    ],
                    'back' => [
                        'url' => route('wiki.articles.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', WikiArticle::class)
                    ]
                ];
            case 'wiki.articles.edit':
                $article = $view->getData()['article'];
                return [
                    'back' => [
                        'url' => route('wiki.articles.show', $article),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $article)
                    ]
                ];

            //
            // Accounting: Transactions
            //
            case 'accounting.transactions.index':
                return [
                    'action' => [
                        'url' => route('accounting.transactions.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', MoneyTransaction::class)
                    ],
                ];
            case 'accounting.transactions.create':
                return [
                    'back' => [
                        'url' => route('accounting.transactions.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', MoneyTransaction::class)
                    ]
                ];
            case 'accounting.transactions.show':
                $transaction = $view->getData()['transaction'];
                return [
                    'back' => [
                        'url' => route('accounting.transactions.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', MoneyTransaction::class)
                    ]
                ];


            //
            // Reporting
            //
            case 'reporting.people':
            case 'reporting.bank.withdrawals':
            case 'reporting.bank.deposits':
            case 'reporting.privacy':
                return [
                    'back' => [
                        'url' => url()->previous(),
                        'caption' => __('app.close'),
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
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
            case 'reporting.article':
                $article = $view->getData()['article'];
                return [
                    'back' => [
                        'url' => route('reporting.articles', $article->project),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => true
                    ]
                ];
        }
        return [];
    }
}
