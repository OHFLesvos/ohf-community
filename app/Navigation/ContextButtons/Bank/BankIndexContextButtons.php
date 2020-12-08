<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Models\Bank\CouponType;
use App\Models\Collaboration\WikiArticle;
use App\Models\People\Person;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BankIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $help_article_id = \Setting::get('bank.help_article');
        $help_article = $help_article_id != null ? WikiArticle::find($help_article_id) : null;
        return [
            'transactions' => [
                'url' => route('bank.withdrawal.transactions'),
                'caption' => __('app.transactions'),
                'icon' => 'list',
                'authorized' => Gate::allows('do-bank-withdrawals') && Auth::user()->can('viewAny', Person::class),
            ],
            'report' => [
                'url' => route('reporting.bank.withdrawals'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-bank-reports'),
            ],
            'export' => [
                'url' => route('bank.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Person::class),
            ],
            'coupons' => [
                'url' => route('bank.coupons.index'),
                'caption' => __('bank.coupons'),
                'icon' => 'ticket-alt',
                'authorized' => Auth::user()->can('viewAny', CouponType::class),
            ],
            'help' => $help_article != null ? [
                'url' => route('kb.articles.show', $help_article),
                'caption' => null,
                'icon' => 'question-circle',
                'authorized' => Auth::user()->can('view', $help_article),
            ] : null,
        ];
    }
}
