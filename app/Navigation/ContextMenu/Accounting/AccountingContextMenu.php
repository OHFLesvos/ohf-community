<?php

namespace App\Navigation\ContextMenu\Accounting;

use App\Models\Accounting\Supplier;
use App\Navigation\ContextMenu\ContextMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountingContextMenu implements ContextMenu
{
    public function getItems(): array
    {
        return [
            'wallets' => [
                'url' => route('accounting.wallets.index'),
                'caption' => __('accounting.wallets'),
                'icon' => 'wallet',
                'authorized' => Gate::allows('configure-accounting'),
            ],
            'suppliers' => [
                'url' => route('accounting.suppliers'),
                'caption' => __('accounting.suppliers'),
                'icon' => 'truck',
                'authorized' => Auth::user()->can('viewAny', Supplier::class) || Gate::allows('manage-suppliers'),
            ],
            'book' => [
                'url' => route('accounting.webling.index'),
                'caption' => __('accounting.book_to_webling'),
                'icon' => 'cloud-upload-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally'),
            ],
        ];
    }
}
