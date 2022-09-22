<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleUserRelationshipController extends Controller
{
    public function index(Role $role): JsonResponse
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return response()
            ->json([
                'data' => [
                    'id' => $role->users->pluck('id'),
                ],
            ]);
    }

    public function store(Role $role, Request $request): JsonResponse
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
                'message' => __('User has been added.'),
            ], Response::HTTP_CREATED);
    }

    public function update(Role $role, Request $request): JsonResponse
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
                'message' => __('User has been updated.'),
            ]);
    }

    public function destroy(Role $role, Request $request): JsonResponse
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
                'message' => __('User has been removed.'),
            ]);
    }
}
