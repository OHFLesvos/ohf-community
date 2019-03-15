<?php

namespace App\Navigation\ContextButtons;

use App\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserShowContextButtons extends BaseContextButtons {

    protected $routeName = 'users.show';

    public function getItems(View $view): array
    {
        $user = $view->getData()['user'];
        return [
            'action' => [
                'url' => route('users.edit', $user),
                'caption' => __('app.edit'),
                'icon' => 'pencil',
                'icon_floating' => 'pencil',
                'authorized' => Auth::user()->can('update', $user)
            ],
            'delete' => [
                'url' => route('users.destroy', $user),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $user),
                'confirmation' => __('app.confirm_delete_user')
            ],
            'back' => [
                'url' => route('users.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', User::class)
            ]
        ];
    }

}
