<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRoleRelationshipController extends Controller
{
    public function index(User $user): JsonResponse
    {
        $this->authorize('view', $user);
        $this->authorize('viewAny', Role::class);

        return response()
            ->json([
                'data' => [
                    'id' => $user->roles->pluck('id'),
                ],
            ]);
    }

    public function store(User $user, Request $request): JsonResponse
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

    public function update(User $user, Request $request): JsonResponse
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

    public function destroy(User $user, Request $request): JsonResponse
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
