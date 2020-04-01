<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('users.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', User::class),
            ],
            'permissions' => [
                'url' => route('users.permissions'),
                'caption' => __('app.permissions'),
                'icon' => 'key',
                'authorized' => Gate::allows('view-usermgmt-reports'),
            ],
            'privacy' => [
                'url' => route('reporting.privacy'),
                'caption' => __('reporting.privacy'),
                'icon' => 'eye',
                'authorized' => Gate::allows('view-usermgmt-reports'),
            ],
        ];
    }

}
