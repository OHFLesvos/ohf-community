<?php

namespace Modules\Bank\Navigation\ContextMenu;

use App\Navigation\ContextMenu\ContextMenu;

use Modules\People\Entities\Person;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BankWithdrawalContextMenu implements ContextMenu {

    public function getItems(): array
    {
        return [
            [
                'url' => route('bank.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Person::class)
            ],
            [
                'url' => route('bank.maintenance'),
                'caption' => __('app.maintenance'),
                'icon' => 'eraser',
                'authorized' => Auth::user()->can('cleanup', Person::class)
            ],
            [
                'url' => route('coupons.index'),
                'caption' => __('bank::coupons.coupons'),
                'icon' => 'ticket-alt',
                'authorized' => Gate::allows('configure-bank')
            ],
            [
                'url' => route('bank.settings.edit'),
                'caption' => __('app.settings'),
                'icon' => 'cogs',
                'authorized' => Gate::allows('configure-bank')
            ],
        ];
    }

}
