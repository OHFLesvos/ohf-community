<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RoleEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $role = $view->getData()['role'];
        return [
            'back' => [
                'url' => route('roles.show', $role),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $role)
            ]
        ];
    }

}
