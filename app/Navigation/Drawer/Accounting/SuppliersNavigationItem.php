<?php

namespace App\Navigation\Drawer\Accounting;

use App\Models\Accounting\Supplier;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SuppliersNavigationItem extends BaseNavigationItem
{
    public function getRoute(): string
    {
        return route('accounting.suppliers');
    }

    public function getCaption(): string
    {
        return __('Suppliers');
    }

    protected $icon = 'truck';

    protected $active = 'accounting/suppliers*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', Supplier::class) || Gate::allows('manage-suppliers');
    }
}
