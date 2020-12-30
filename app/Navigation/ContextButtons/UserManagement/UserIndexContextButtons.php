<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\Role;
use App\Navigation\ContextButtons\ContextButtons;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('users.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', User::class),
            ],
            'roles' => [
                'url' => route('roles.index'),
                'caption' => __('app.roles'),
                'icon' => 'tags',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
            'permissions' => [
                'url' => route('users.permissions'),
                'caption' => __('app.permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }

}
