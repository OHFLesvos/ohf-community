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
                'members' => [
                'url' => route('roles.manageMembers', $role),
                'caption' => __('Manage members'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('manageMembers', $role) && ! Auth::user()->can('update', $role),
            ],
        ];
    }
}
