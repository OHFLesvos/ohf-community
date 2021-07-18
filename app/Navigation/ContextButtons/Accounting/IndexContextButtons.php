<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class IndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'summary' => [
                'url' => route('accounting.transactions.summary'),
                'caption' => __('Summary'),
                'icon' => 'globe',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
        ];
    }
}
