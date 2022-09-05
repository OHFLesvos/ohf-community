<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
        $data['num_transactions'] = $this->transactions()->count();
        $data['num_donations'] = $this->whenLoaded('donations', fn () => $this->donations()->count());
        $data['sum_donations'] = $this->whenLoaded('donations', fn () => number_format($this->donations()->sum('exchange_amount'), 2).' '.config('fundraising.base_currency'));
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);

        return $data;
    }
}
