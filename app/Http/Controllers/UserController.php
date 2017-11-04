<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', User::class);

        return view('users.index', [
            'users' => User::orderBy('name')
                ->paginate(),
            'buttons' => [
                'action' => [
                    'url' => route('users.create'),
                    'caption' => 'Add',
                    'icon' => 'plus-circle',
                    'icon_floating' => 'plus',
                    'authorized' => Auth::user()->can('create', User::class)
                ]
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create', [
            'roles' => Role::orderBy('name')->get(),
            'buttons' => [
                'back' => [
                    'url' => route('users.index'),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', User::class)
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $this->authorize('create', User::class);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_super_admin = !empty($request->is_super_admin);
        $user->save();
        $user->roles()->sync($request->roles);
        return redirect()->route('users.index')
            ->with('success', 'User has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.show', [
            'user' => $user,
            'buttons' => [
                'action' => [
                    'url' => route('users.edit', $user),
                    'caption' => 'Edit',
                    'icon' => 'pencil',
                    'icon_floating' => 'pencil',
                    'authorized' => Auth::user()->can('update', $user)
                ],
                'delete' => [
                    'url' => route('users.destroy', $user),
                    'caption' => 'Delete',
                    'icon' => 'trash',
                    'authorized' => Auth::user()->can('delete', $user),
                    'confirmation' => 'Really delete this user?'
                ],
                'back' => [
                    'url' => route('users.index'),
                    'caption' => 'Close',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('list', User::class)
                ]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'buttons' => [
                'back' => [
                    'url' => route('users.show', $user),
                    'caption' => 'Cancel',
                    'icon' => 'times-circle',
                    'authorized' => Auth::user()->can('view', $user)
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $this->authorize('update', $user);

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->is_super_admin = !empty($request->is_super_admin) || User::count() == 1;
        $user->roles()->sync($request->roles);
        if ($user->isDirty()) {
            $user->save();
            return redirect()->route('users.show', $user)
                ->with('success', 'User has been updated.');
        }
        return redirect()->route('users.show', $user)
            ->with('info', 'No changes have been made.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User has been deleted.');
    }
}
