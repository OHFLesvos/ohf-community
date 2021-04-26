<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $user = $view->getData()['user'];
        return [
            'action' => [
                'url' => route('users.edit', $user),
                'caption' => __('Edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $user),
            ],
            'delete' => [
                'url' => route('users.destroy', $user),
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $user),
                'confirmation' => __('Really delete this user?'),
            ],
            'back' => [
                'url' => route('users.index'),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }
}
