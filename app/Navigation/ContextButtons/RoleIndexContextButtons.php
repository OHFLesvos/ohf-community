<?php

namespace App\Navigation\ContextButtons;

use App\Role;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('roles.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Role::class)
            ],
            'permissions' => [
                'url' => route('roles.permissions'),
                'caption' => __('app.permissions'),
                'icon' => 'key',
                'authorized' => Gate::allows('view-usermgmt-reports')
            ]
        ];
    }

}
