<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $user = $view->getData()['user'];
        return [
            'back' => [
                'url' => route('users.show', $user),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $user),
            ],
        ];
    }

}
