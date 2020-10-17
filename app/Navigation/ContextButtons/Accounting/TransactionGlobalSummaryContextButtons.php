<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TransactionGlobalSummaryContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        // TODO implement back-button if coming from wallet summary view
        return [
            'close' => [
                'url' => route('accounting.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
        ];
    }

}
