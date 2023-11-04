<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\User;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'permissions' => [
                'url' => route('users.permissions'),
                'caption' => __('View Permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }
}
