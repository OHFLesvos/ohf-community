<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\Role;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $role = $view->getData()['role'];

        return [
            'action' => [
                'url' => route('roles.edit', $role),
                'caption' => __('Edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $role),
            ],
            'members' => [
                'url' => route('roles.manageMembers', $role),
                'caption' => __('Manage members'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('manageMembers', $role) && ! Auth::user()->can('update', $role),
            ],
            'delete' => [
                'url' => route('roles.destroy', $role),
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $role),
                'confirmation' => __('Really delete this role?'),
            ],
            'back' => [
                'url' => route('roles.index'),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }
}
