<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $role = $view->getData()['role'];

        return [
            'back' => [
                'url' => route('roles.show', $role),
                'caption' => __('Cancel'),
                'icon' => 'circle-xmark',
                'authorized' => Auth::user()->can('view', $role),
            ],
        ];
    }
}
