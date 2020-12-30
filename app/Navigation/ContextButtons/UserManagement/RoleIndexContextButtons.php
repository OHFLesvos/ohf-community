<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('roles.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Role::class),
            ],
            'users' => [
                'url' => route('users.index'),
                'caption' => __('app.users'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
            'permissions' => [
                'url' => route('roles.permissions'),
                'caption' => __('app.permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }

}
