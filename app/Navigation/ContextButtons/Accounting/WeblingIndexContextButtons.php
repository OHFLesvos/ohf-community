<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Transaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeblingIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];

        return [
            'back' => [
                'url' => route('accounting.transactions.index', ['wallet' => $wallet->id]),
                'caption' => __('Close'),
                'icon' => 'circle-xmark',
                'authorized' => Auth::user()->can('viewAny', Transaction::class),
            ],
        ];
    }
}
