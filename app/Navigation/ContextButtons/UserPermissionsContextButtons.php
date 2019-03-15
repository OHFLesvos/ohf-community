<?php

namespace App\Navigation\ContextButtons;

use App\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserPermissionsContextButtons extends BaseContextButtons {

    protected $routeName = 'users.permissions';

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => url()->previous(),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', User::class)
            ]
        ];
    }

}
