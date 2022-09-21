<?php

namespace App\Http\Resources;

use App\Http\Resources\Role as RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
            'is_super_admin' => $this->is_super_admin,
            'is_2fa_enabled' => $this->tfa_secret !== null,
            'avatar_url' => $this->avatarUrl(),
            'provider_name' => $this->provider_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => $this->links(),
            'relationships' => $this->relationships(),
            'is_current_user' => $this->id == Auth::id(),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }

    private function links()
    {
        return [
            'self' => route('api.users.show', $this->resource),
            'parent' => route('api.users.index'),
            'show' => route('users.show', $this->resource),
            'edit' => route('users.edit', $this->resource),
        ];
    }

    private function relationships()
    {
        return [
            'roles' => [
                'data' => RoleResource::collection($this->whenLoaded('roles')),
                'links' => [
                    'self' => route('api.users.relationships.roles.index', $this->resource),
                    'related' => route('api.users.roles.index', $this->resource),
                ],
            ],
            'administeredRoles' => [
                'data' => RoleResource::collection($this->whenLoaded('administeredRoles')),
                'links' => [
                    'self' => route('api.users.relationships.roles.index', $this->resource),
                    'related' => route('api.users.roles.index', $this->resource),
                ],
            ],
        ];
    }
}
