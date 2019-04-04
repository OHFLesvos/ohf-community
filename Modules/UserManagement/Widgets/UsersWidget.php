<?php

namespace Modules\UserManagement\Widgets;

use App\User;
use App\Widgets\Widget;

use Illuminate\Support\Facades\Auth;

class UsersWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', User::class);
    }

    function view(): string
    {
        return 'usermanagement::dashboard.widgets.users';
    }

    function args(): array {
        return [
            'num_users' => User::count(),
			'latest_user' => User::orderBy('created_at', 'desc')->first(),
        ];
    }
}