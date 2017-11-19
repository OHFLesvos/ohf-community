<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class UserController extends ParentController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::with(['roles'])->orderBy('name')->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
            'roles' => Role::orderBy('name')->get()
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
        return view('users.show', [
            'user' => $user
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
        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get()
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
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User has been deleted.');
    }
}
