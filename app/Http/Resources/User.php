<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'avatar' => $this->avatar,
            'provider_name' => $this->provider_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => $this->links(),
            'relationships' => $this->relationships(),
        ];
    }

    private function links()
    {
        return [
            'self' => route('api.users.show', $this->resource),
            'parent' => route('api.users.index'),
        ];
    }

    private function relationships()
    {
        return [
            'roles' => [
                'links' => [
                    'self' => route('api.users.relationships.roles.index', $this->resource),
                    'related' => route('api.users.roles.index', $this->resource),
                ],
            ],
        ];
    }
}
