<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeopleCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('bank.withdrawal.search'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('do-bank-withdrawals'),
            ],
        ];
    }
}
