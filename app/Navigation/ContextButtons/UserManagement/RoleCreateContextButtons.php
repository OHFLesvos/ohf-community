<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\Role;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('roles.index'),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }
}
