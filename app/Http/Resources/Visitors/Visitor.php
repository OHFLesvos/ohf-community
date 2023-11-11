<?php

namespace App\Http\Resources\Visitors;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Visitors\Visitor
 */
class Visitor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            ...parent::toArray($request),
            'checked_in_today' => $this->whenLoaded('checkins',
                fn () => $this->checkins->contains(fn ($checkin) => $checkin->checkin_date->isToday())),
            'parents' => $this->whenLoaded('parents',
                fn () => $this->parents->map(fn ($parent) => [
                    'id' => $parent->id,
                    'name' => $parent->name,
                ])),
            'children' => $this->whenLoaded('children',
                fn () => $this->children->map(fn ($child) => [
                    'id' => $child->id,
                    'name' => $child->name,
                ])),
        ];
    }
}
