<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\Role;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('roles.create'),
                'caption' => __('Add'),
                'icon' => 'circle-plus',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Role::class),
            ],
            'users' => [
                'url' => route('users.index'),
                'caption' => __('Users'),
                'icon' => 'users',
                'active' => 'admin/users*',
                'authorized' => Auth::user()->can('viewAny', App\Models\User::class),
            ],
            'permissions' => [
                'url' => route('roles.permissions'),
                'caption' => __('View Permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }
}
