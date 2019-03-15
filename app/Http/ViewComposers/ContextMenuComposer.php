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

use Modules\Accounting\Entities\MoneyTransaction;
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

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    private function getButtons(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {
            //
            // Bank
            //

            // Shop
            case 'shop.index':
                return [
                    'settings' => [
                        'url' => route('shop.settings.edit'),
                        'caption' => __('app.settings'),
                        'icon' => 'cogs',
                        'authorized' => Gate::allows('configure-shop')
                    ]
                ];
            case 'shop.settings.edit':
                return [
                    'back' => [
                        'url' => route('shop.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('validate-shop-coupons')
                    ]
                ];

            // Barber
            case 'shop.barber.index':
                return [
                    'settings' => [
                        'url' => route('shop.barber.settings.edit'),
                        'caption' => __('app.settings'),
                        'icon' => 'cogs',
                        'authorized' => Gate::allows('configure-barber-list')
                    ]
                ];
            case 'shop.barber.settings.edit':
                return [
                    'back' => [
                        'url' => route('shop.barber.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('view-barber-list')
                    ]
                ];

            //
            // Library
            //
            case 'library.lending.index':
                return [
                    'settings' => [
                        'url' => route('library.settings.edit'),
                        'caption' => __('app.settings'),
                        'icon' => 'cogs',
                        'authorized' => Gate::allows('configure-library')
                    ]
                ];
            case 'library.settings.edit':
                return [
                    'back' => [
                        'url' => route('library.lending.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('operate-library'),
                    ]
                ];
            case 'library.lending.persons':
            case 'library.lending.books':
                return [
                    'back' => [
                        'url' => route('library.lending.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('operate-library'),
                    ]
                ];
            case 'library.lending.person':
                $person = $view->getData()['person'];
                return [
                    'log' => [
                        'url' => route('library.lending.personLog', $person),
                        'caption' => __('app.log'),
                        'icon' => 'list',
                        'authorized' => $person->bookLendings()->count() > 0 && Auth::user()->can('list', Person::class),
                    ],
                    'person' => [
                        'url' => route('people.show', $person),
                        'caption' => __('people.view_person'),
                        'icon' => 'users',
                        'authorized' => Auth::user()->can('view', $person),
                    ],
                    'back' => [
                        'url' => route('library.lending.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('operate-library'),
                    ],
                ];
            case 'library.lending.personLog':
                $person = $view->getData()['person'];
                return [
                    'back' => [
                        'url' => route('library.lending.person', $person),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Person::class),
                    ]
                ];
            case 'library.lending.book':
                $book = $view->getData()['book'];
                return [
                    'log' => [
                        'url' => route('library.lending.bookLog', $book),
                        'caption' => __('app.log'),
                        'icon' => 'list',
                        'authorized' => $book->lendings()->count() > 0 && Auth::user()->can('view', $book),
                    ],
                    'edit' => [
                        'url' => route('library.books.edit', $book),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'authorized' => Auth::user()->can('update', $book),
                    ],
                    'back' => [
                        'url' => route('library.lending.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('operate-library'),
                    ],
                ];
            case 'library.lending.bookLog':
                $book = $view->getData()['book'];
                return [
                    'back' => [
                        'url' => route('library.lending.book', $book),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $book),
                    ]
                ];
            case 'library.books.index':
                return [
                    'action' => [
                        'url' => route('library.books.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', \App\LibraryBook::class)
                    ],
                ];
            case 'library.books.create':
                return [
                    'back' => [
                        'url' => route('library.books.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', \App\LibraryBook::class),
                    ]
                ];
            case 'library.books.edit':
                $book = $view->getData()['book'];
                return [
                    'delete' => [
                        'url' => route('library.books.destroy', $book),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $book),
                        'confirmation' => __('library.confirm_delete_book')
                    ],
                    'back' => [
                        'url' => route('library.lending.book', $book),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $book),
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
            case 'fundraising.donations.index':
                return [
                    'import' => [
                        'url' => route('fundraising.donations.import'),
                        'caption' => __('app.import'),
                        'icon' => 'upload',
                        'authorized' => Auth::user()->can('create', Donation::class)
                    ]
                ];
            case 'fundraising.donations.import':
                return [
                    'back' => [
                        'url' => route('fundraising.donations.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Donation::class)
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
                    'export' => [
                        'url' => route('accounting.transactions.export'),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', MoneyTransaction::class)
                    ],
                ];
            case 'accounting.transactions.summary':
                return [
                    'export' => [
                        'url' => route('accounting.transactions.export'),
                        'caption' => __('app.export'),
                        'icon' => 'download',
                        'authorized' => Auth::user()->can('list', MoneyTransaction::class)
                    ],
                ];
            case 'accounting.transactions.create':
            case 'accounting.transactions.export':
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
                    'action' => [
                        'url' => route('accounting.transactions.edit', $transaction),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
                        'authorized' => Auth::user()->can('update', $transaction)
                    ],
                    'receipt' => [
                        'url' => route('accounting.transactions.editReceipt', $transaction),
                        'caption' => __('accounting::accounting.receipt'),
                        'icon' => 'list-ol',
                        'authorized' => Auth::user()->can('update', $transaction),
                    ],
                    'delete' => [
                        'url' => route('accounting.transactions.destroy', $transaction),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $transaction),
                        'confirmation' => __('accounting::accounting.confirm_delete_transaction')
                    ],
                    'back' => [
                        'url' => route('accounting.transactions.index'),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', MoneyTransaction::class)
                    ]
                ];
            case 'accounting.transactions.edit':
                $transaction = $view->getData()['transaction'];
                return [
                    'back' => [
                        'url' => route('accounting.transactions.show', $transaction),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $transaction)
                    ]
                ];
            case 'accounting.transactions.editReceipt':
                $transaction = $view->getData()['transaction'];
                return [
                    'back' => [
                        'url' => route('accounting.transactions.show', $transaction),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $transaction)
                    ]
                ];

            //
            // Inventory
            //
            case 'inventory.storages.index':
                return [
                    'add' => [
                        'url' => route('inventory.storages.create'),
                        'caption' => __('inventory.add_storage'),
                        'icon' => 'plus-circle',
                        'authorized' => Auth::user()->can('create', InventoryStorage::class),
                    ],
                ];
            case 'inventory.storages.create':
                return [
                    'back' => [
                        'url' => route('inventory.storages.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', InventoryStorage::class),
                    ],
                ];
            case 'inventory.storages.show':
                $storage = $view->getData()['storage'];
                return [
                    'action' => [
                        'url' => route('inventory.transactions.ingress', $storage),
                        'caption' => __('inventory.store_items'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', InventoryItemTransaction::class),
                    ],
                    'edit' => [
                        'url' => route('inventory.storages.edit', $storage),
                        'caption' => __('inventory.edit_storage'),
                        'icon' => 'pencil',
                        'authorized' => Auth::user()->can('edit', $storage),
                    ],
                    'delete' => [
                        'url' => route('inventory.storages.destroy', $storage),
                        'caption' => __('inventory.delete_storage'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $storage),
                        'confirmation' => __('inventory.confirm_delete_storage')
                    ],
                    'back' => [
                        'url' => route('inventory.storages.index'),
                        'caption' => __('app.overview'),
                        'icon' => 'list',
                        'authorized' => Auth::user()->can('list', InventoryStorage::class),
                    ]
                ];
            case 'inventory.storages.edit':
            $storage = $view->getData()['storage'];
                return [
                    'back' => [
                        'url' => route('inventory.storages.show', $storage),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $storage),
                    ],
                ];
            case 'inventory.transactions.changes':
                $storage = $view->getData()['storage'];
                $numTransactions = InventoryItemTransaction::where('item', request()->item)->count();
                $sum = InventoryItemTransaction::where('item', request()->item)->groupBy('item')->select(DB::raw('SUM(quantity) as sum'), 'item')->orderBy('item')->first()->sum;
                return [
                    'add' => [
                        'url' => route('inventory.transactions.ingress', $storage) . '?item=' . request()->item,
                        'caption' => __('inventory.store_items'),
                        'icon' => 'plus-circle',
                        'authorized' => $numTransactions > 0 && Auth::user()->can('create', InventoryItemTransaction::class),
                    ],
                    'remove' => [
                        'url' => route('inventory.transactions.egress', $storage) . '?item=' . request()->item,
                        'caption' => __('inventory.take_out_items'),
                        'icon' => 'minus-circle',
                        'authorized' => $numTransactions > 0 && $sum > 0 && Auth::user()->can('create', InventoryItemTransaction::class),
                    ],
                    'delete' => [
                        'url' => route('inventory.transactions.destroy', $storage) . '?item=' . request()->item,
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => $numTransactions > 0 && Auth::user()->can('delete', InventoryItemTransaction::class),
                        'confirmation' => __('inventory.confirm_delete_item')
                    ],
                    'back' => [
                        'url' => route('inventory.storages.show', $storage),
                        'caption' => __('app.close'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $storage),
                    ]
                ];
            case 'inventory.transactions.ingress':
            case 'inventory.transactions.egress':
                $storage = $view->getData()['storage'];
                return [
                    'back' => [
                        'url' => route('inventory.storages.show', $storage),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('view', $storage),
                    ]
                ];

            //
            // Badges
            //
            case 'badges.selection':
                return [
                    'back' => [
                        'url' => route('badges.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Gate::allows('create-badges'),
                    ],
                ];

            //
            // Reporting
            //
            case 'reporting.monthly-summary':
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
