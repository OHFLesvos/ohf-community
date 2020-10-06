<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class Supplier extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);
        $data['transactions_count'] = $this->transactions()->count();
        $data['spending'] = $this->when($request->has('with_spending'), fn () => $this->transactions()->where('type', 'spending')->sum('amount'));
        return $data;
    }
}
