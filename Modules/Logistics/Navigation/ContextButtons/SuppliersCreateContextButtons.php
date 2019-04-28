<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Logistics\Entities\Supplier;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SuppliersCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('logistics.suppliers.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('create', Supplier::class)
            ]
        ];
    }

}
