<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\User;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('users.index'),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }
}
