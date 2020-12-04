<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleUserRelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return [
            'data' => [
                'id' => $role->users->pluck('id'),
            ],
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Role $role, Request $request)
    {
        $this->authorize('update', $role);

        $request->validate([
            'id' => [
                'required',
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:users,id',
            ],
        ]);

        $role->users()->syncWithoutDetaching($request->id);

        return response()
            ->json([
                'message' => __('app.user_added'),
            ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        $this->authorize('update', $role);

        $request->validate([
            'id' => [
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:users,id',
            ],
        ]);

        $role->users()->sync($request->id);

        return response()
            ->json([
                'message' => __('app.user_updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, Request $request)
    {
        $this->authorize('update', $role);

        $request->validate([
            'id' => [
                'required',
                'array',
            ],
            'id.*' => [
                'integer',
                'exists:users,id',
            ],
        ]);

        $role->users()->detach($request->id);

        return response()
            ->json([
                'message' => __('app.user_removed'),
            ]);
    }
}
