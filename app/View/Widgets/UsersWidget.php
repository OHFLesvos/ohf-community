<?php

namespace App\View\Widgets;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', User::class);
    }

    public function view(): string
    {
        return 'widgets.users';
    }

    public function args(): array
    {
        return [
            'num_users' => User::count(),
            'latest_user' => User::orderBy('created_at', 'desc')->first(),
        ];
    }
}
