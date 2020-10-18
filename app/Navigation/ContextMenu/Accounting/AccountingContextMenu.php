<?php

namespace App\Navigation\ContextMenu\Accounting;

use App\Models\Accounting\Supplier;
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
            'book' => [
                'url' => route('accounting.webling.index', $wallet),
                'caption' => __('accounting.book_to_webling'),
                'icon' => 'cloud-upload-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally'),
            ],
        ];
    }
}
