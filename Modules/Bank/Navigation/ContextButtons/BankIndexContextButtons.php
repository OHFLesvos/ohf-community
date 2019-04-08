<?php

namespace Modules\Bank\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\People\Entities\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BankIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('people.create'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Person::class)
            ],
            'transactions' => [
                'url' => route('bank.withdrawalTransactions'),
                'caption' => __('app.transactions'),
                'icon' => 'list',
                'authorized' => Gate::allows('do-bank-withdrawals')
            ],
            'codecard' => [
                'url' => route('bank.prepareCodeCard'),
                'caption' => __('people::people.code_cards'),
                'icon' => 'qrcode',
                'authorized' => Gate::allows('do-bank-withdrawals')
            ],
            'report'=> [
                'url' => route('reporting.bank.withdrawals'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-bank-reports')
            ]
        ];
    }

}
