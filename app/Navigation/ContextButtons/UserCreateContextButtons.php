<?php

namespace App\Navigation\ContextButtons;

use App\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserCreateContextButtons extends BaseContextButtons {

    protected $routeName = 'users.create';

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('users.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', User::class)
            ]
        ];
    }

}
