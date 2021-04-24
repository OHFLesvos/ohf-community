<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WalletsContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'overview' => [
                'url' => route('accounting.index'),
                'caption' => __('app.overview'),
                'icon' => 'money-bill-alt',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class) || Gate::allows('view-accounting-summary'),
            ],
        ];
    }
}
