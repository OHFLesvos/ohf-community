<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Models\User;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserPermissionsContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => url()->previous(),
                'caption' => __('Close'),
                'icon' => 'circle-xmark',
                'authorized' => Auth::user()->can('viewAny', User::class),
            ],
        ];
    }
}
