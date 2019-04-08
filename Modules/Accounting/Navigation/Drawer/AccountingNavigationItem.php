<?php

namespace Modules\Accounting\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountingNavigationItem extends BaseNavigationItem {

    public function getRoute(): string
    {
        return !Auth::user()->can('list', MoneyTransaction::class) && Gate::allows('view-accounting-summary') 
            ? route('accounting.transactions.summary')
            : route('accounting.transactions.index');
    }

    protected $caption = 'accounting::accounting.accounting';

    protected $icon = 'money-bill-alt';

    protected $active = 'accounting/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', MoneyTransaction::class) || Gate::allows('view-accounting-summary');
    }

}
