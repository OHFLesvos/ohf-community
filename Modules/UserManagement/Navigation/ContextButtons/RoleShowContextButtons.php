<?php

namespace Modules\UserManagement\Navigation\ContextButtons;

use App\Role;
use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RoleShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $role = $view->getData()['role'];
        return [
            'action' => [
                'url' => route('roles.edit', $role),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $role)
            ],
            'members' => [
                'url' => route('roles.manageMembers', $role),
                'caption' => __('app.manage_members'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('manageMembers', $role) && !Auth::user()->can('update', $role)
            ],
            'delete' => [
                'url' => route('roles.destroy', $role),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $role),
                'confirmation' => __('app.confirm_delete_role')
            ],
            'back' => [
                'url' => route('roles.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Role::class)
            ]
        ];
    }

}
