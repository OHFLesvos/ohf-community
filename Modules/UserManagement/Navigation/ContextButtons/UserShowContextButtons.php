<?php

namespace Modules\UserManagement\Navigation\ContextButtons;

use App\User;
use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $user = $view->getData()['user'];
        return [
            'action' => [
                'url' => route('users.edit', $user),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
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
