<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Transaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionReturnToIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];
        return [
            'back' => [
                'url' => route('accounting.transactions.index', $wallet),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Transaction::class),
            ],
        ];
    }
}
