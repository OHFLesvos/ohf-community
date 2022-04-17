<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use App\Models\Role;
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
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Role::class),
            ],
            'permissions' => [
                'url' => route('roles.permissions'),
                'caption' => __('Permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }
}
