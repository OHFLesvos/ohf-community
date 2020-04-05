<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function($resource) {
            $resource->includeLinks = true;
            $resource->includeRelationships = true;
            return $resource;
        });
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'links' => $this->links(),
            'meta' => [
                'total' => $this->count(),
            ],
        ];
    }

    private function links()
    {
        return [
            'self' => route('api.roles.index'),
        ];
    }
}
