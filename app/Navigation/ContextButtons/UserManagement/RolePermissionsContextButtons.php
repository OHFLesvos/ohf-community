<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\Role;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RolePermissionsContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => url()->previous(),
                'caption' => __('Close'),
                'icon' => 'circle-plus',
                'authorized' => Auth::user()->can('viewAny', Role::class),
            ],
        ];
    }
}
