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
            'action' => [
                'url' => route('users.create'),
                'caption' => __('Add'),
                'icon' => 'circle-plus',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', User::class),
            ],
            'permissions' => [
                'url' => route('users.permissions'),
                'caption' => __('Permissions'),
                'icon' => 'key',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }
}
