<?php

namespace App\Navigation;

use App\MoneyTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountingNavigationItem extends BaseNavigationItem {

    public function getRoute(): string
    {
        return !Auth::user()->can('list', MoneyTransaction::class) && Gate::allows('view-accounting-summary') ? 'accounting.transactions.summary' : 'accounting.transactions.index';
    }

    protected $caption = 'accounting::accounting.accounting';

    protected $icon = 'money';

    protected $active = 'accounting/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', MoneyTransaction::class) || Gate::allows('view-accounting-summary');
    }

}
