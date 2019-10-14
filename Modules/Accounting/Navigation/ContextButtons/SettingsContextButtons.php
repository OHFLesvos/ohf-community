<?php

namespace Modules\Accounting\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class SettingsContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('accounting.transactions.summary'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('view-accounting-summary'),
            ]
        ];
    }

}
