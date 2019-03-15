<?php

namespace App\Navigation\ContextButtons;

use App\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserPermissionsContextButtons implements ContextButtons {

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
