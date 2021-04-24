<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TransactionIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];
        return [
            'action' => [
                'url' => route('accounting.transactions.create', $wallet),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', MoneyTransaction::class),
            ],
            'summary' => [
                'url' => route('accounting.transactions.summary', $wallet),
                'caption' => __('app.summary'),
                'icon' => 'calculator',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
            'export' => [
                'url' => route('accounting.transactions.export', $wallet),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ],
            'webling' => [
                'url' => route('accounting.webling.index', $wallet),
                'caption' => __('app.webling'),
                'icon' => 'cloud-upload-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally')
                    && config('services.webling.api_url') !== null
                    && config('services.webling.api_key') !== null,
            ],
        ];
    }
}
