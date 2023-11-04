<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Util\AutoColorInitialAvatar;
use App\View\Components\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    public function permissions(): View
    {
        $this->authorize('viewAny', User::class);

        return view('user_management.users.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function avatar(User $user, Request $request): Response
    {
        $request->validate([
            'size' => [
                'nullable',
                'integer',
                'min:10',
                'max:250',
            ],
        ]);

        $avatar = new AutoColorInitialAvatar();

        return $avatar->name($user->name)
            ->size($request->input('size', UserAvatar::DEFAULT_SIZE))
            ->autoColor()
            ->generate()
            ->response('png', 100);
    }
}
