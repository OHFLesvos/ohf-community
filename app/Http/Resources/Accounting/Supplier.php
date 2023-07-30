<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Accounting\Supplier
 */
class Supplier extends JsonResource
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
            'can_view' => $request->user()->can('view', $this->resource),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'transactions_count' => $this->transactions()->count(),
            'spending' => $this->when($request->has('with_spending'),
                fn () => $this->transactions()->where('type', 'spending')->sum('amount')),
        ];
    }
}
