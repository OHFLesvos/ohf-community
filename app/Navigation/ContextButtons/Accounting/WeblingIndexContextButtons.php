<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Accounting\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WeblingIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('accounting.transactions.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', MoneyTransaction::class),
            ]
        ];
    }

}
