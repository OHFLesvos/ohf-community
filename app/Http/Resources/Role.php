<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Role
 */
class Role extends JsonResource
{
    public $includeLinks = false;

    public $includeRelationships = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'num_users' => $this->users()->count(),
            'num_administrators' => $this->administrators()->count(),
            'num_permissions' => $this->permissions->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => $this->links(),
            'relationships' => $this->relationships(),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'can_manage_members' => $request->user()->can('manageMembers', $this->resource) && ! $request->user()->can('update', $this->resource),
        ];
    }

    private function links()
    {
        return [
            'self' => route('api.roles.show', $this->resource),
            'parent' => route('api.roles.index'),
            'show' => route('roles.show', $this->resource),
            'edit' => route('roles.edit', $this->resource),
        ];
    }

    private function relationships()
    {
        return [
            'users' => [
                'data' => UserResource::collection($this->whenLoaded('users')),
                'links' => [
                    'self' => route('api.roles.relationships.users.index', $this->resource),
                    'related' => route('api.roles.users.index', $this->resource),
                ],
                // 'total' => $this->resource->users->count(),
            ],
            'administrators' => [
                'links' => [
                    'self' => route('api.roles.relationships.administrators.index', $this->resource),
                    'related' => route('api.roles.administrators.index', $this->resource),
                ],
                // 'total' => $this->resource->administrators->count(),
            ],
        ];
    }
}
