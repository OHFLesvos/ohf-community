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

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    private function getButtons(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {
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
