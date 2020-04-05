<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    public $includeLinks = false;
    public $includeRelationships = false;

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => $this->when($this->includeLinks, $this->links()),
            'relationships' => $this->when($this->includeRelationships, $this->relationships()),
        ];
    }

    public function with($request)
    {
        return [
            'links' => $this->links(),
            'relationships' => $this->relationships(),
        ];
    }

    private function links()
    {
        return [
            'self' => route('api.roles.show', $this->resource),
            'parent' => route('api.roles.index'),
        ];
    }

    private function relationships()
    {
        return [
            'users' => [
                'links' => [
                    'self' => route('api.roles.relationships.users.index', $this->resource),
                    'related' => route('api.roles.users.index', $this->resource),
                ],
                'total' => $this->resource->users->count(),
            ],
            'administrators' => [
                'links' => [
                    'self' => route('api.roles.relationships.administrators.index', $this->resource),
                    'related' => route('api.roles.administrators.index', $this->resource),
                ],
                'total' => $this->resource->administrators->count(),
            ],
        ];
    }
}
