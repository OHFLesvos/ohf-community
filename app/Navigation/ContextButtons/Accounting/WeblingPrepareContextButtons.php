<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeblingPrepareContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];

        return [
            'back' => [
                'url' => route('accounting.webling.index', $wallet),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally'),
            ],
        ];
    }
}
