<?php

namespace App\Http\Resources\CommunityVolunteers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Responsibility extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }
}
