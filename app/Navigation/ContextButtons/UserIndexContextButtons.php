<?php

namespace App\Navigation\ContextButtons;

use App\User;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserIndexContextButtons extends BaseContextButtons {

    protected $routeName = 'users.index';

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('users.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', User::class)
            ],
            'permissions' => [
                'url' => route('users.permissions'),
                'caption' => __('app.permissions'),
                'icon' => 'key',
                'authorized' => Gate::allows('view-usermgmt-reports')
            ],
            'privacy' => [
                'url' => route('reporting.privacy'),
                'caption' => __('reporting.privacy'),
                'icon' => 'eye',
                'authorized' => Gate::allows('view-usermgmt-reports')
            ],                    
        ];
    }

}
