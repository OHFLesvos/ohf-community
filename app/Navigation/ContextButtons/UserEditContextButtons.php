<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserEditContextButtons extends BaseContextButtons {

    protected $routeName = 'users.edit';

    public function getItems(View $view): array
    {
        $user = $view->getData()['user'];
        return [
            'back' => [
                'url' => route('users.show', $user),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $user)
            ]
        ];
    }

}
