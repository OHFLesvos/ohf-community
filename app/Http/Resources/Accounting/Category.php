<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Accounting\Category
 */
class Category extends JsonResource
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
            'num_transactions' => $this->transactions()->count(),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }
}
