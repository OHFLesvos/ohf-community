<?php

namespace Modules\UserManagement\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserEditContextButtons implements ContextButtons {

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
