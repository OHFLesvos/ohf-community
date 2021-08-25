<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Transaction;
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
            'summary' => [
                'url' => route('accounting.transactions.summary', ['wallet' => $wallet]),
                'caption' => __('Summary'),
                'icon' => 'calculator',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
            'webling' => [
                'url' => route('accounting.webling.index', $wallet),
                'caption' => __('Webling'),
                'icon' => 'cloud-upload-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally')
                    && config('services.webling.api_url') !== null
                    && config('services.webling.api_key') !== null,
            ],
        ];
    }
}
