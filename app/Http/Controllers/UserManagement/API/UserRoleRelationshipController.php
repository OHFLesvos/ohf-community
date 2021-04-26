<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRoleRelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $this->authorize('view', $user);
        $this->authorize('viewAny', Role::class);

        return [
            'data' => [
                'id' => $user->roles->pluck('id'),
            ],
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $request->validate([
            'id' => [
                'required',
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:roles,id',
            ],
        ]);

        $user->roles()->syncWithoutDetaching($request->id);

        return response()
            ->json([
                'message' => __('Role has been added.'),
            ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $request->validate([
            'id' => [
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:roles,id',
            ],
        ]);

        $user->roles()->sync($request->id);

        return response()
            ->json([
                'message' => __('Role has been updated.'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $request->validate([
            'id' => [
                'required',
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:roles,id',
            ],
        ]);

        $user->roles()->detach($request->id);

        return response()
            ->json([
                'message' => __('Role has been removed.'),
            ]);
    }
}
