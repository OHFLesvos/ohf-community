<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Accounting\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WeblingPrepareContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('accounting.webling.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally'),
            ]
        ];
    }

}
