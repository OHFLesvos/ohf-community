<?php

namespace App\Navigation\Drawer\Accounting;

use App\Models\Accounting\Transaction;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountingNavigationItem extends BaseNavigationItem
{
    public function getRoute(): string
    {
        return route('accounting.index');
    }

    public function getCaption(): string
    {
        return __('Accounting');
    }

    protected $icon = 'money-bill-alt';

    protected $active = 'accounting*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', Transaction::class) || Gate::allows('view-accounting-summary');
    }
}
