<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeopleShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'action' => [
                'url' => route('bank.people.edit', $person),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $person),
            ],
            'delete' => [
                'url' => route('bank.people.destroy', $person),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $person),
                'confirmation' => __('people.confirm_delete_person'),
            ],
            'back' => [
                'url' => route('bank.withdrawal.search'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('do-bank-withdrawals'),
            ],
        ];
    }
}
