<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Transaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectsContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'overview' => [
                'url' => route('accounting.index'),
                'caption' => __('Overview'),
                'icon' => 'money-bill-alt',
                'authorized' => Auth::user()->can('viewAny', Transaction::class) || Gate::allows('view-accounting-summary'),
            ],
        ];
    }
}
