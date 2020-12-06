<?php

namespace App\Navigation\ContextMenu\Accounting;

use App\Navigation\ContextMenu\ContextMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AccountingContextMenu implements ContextMenu
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];
        return [
            'suppliers' => [
                'url' => route('accounting.suppliers'),
                'caption' => __('accounting.suppliers'),
                'icon' => 'truck',
                'authorized' => Auth::user()->can('viewAny', Supplier::class) || Gate::allows('manage-suppliers'),
            ],
            'book' => [
                'url' => route('accounting.webling.index', $wallet),
                'caption' => __('accounting.book_to_webling'),
                'icon' => 'cloud-upload-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally')
                    && config('services.webling.api_url') !== null
                    && config('services.webling.api_key') !== null,
            ],
        ];
    }
}
