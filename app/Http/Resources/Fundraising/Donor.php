<?php

namespace App\Http\Resources\Fundraising;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Fundraising\Donor
 */
class Donor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'full_name' => $this->full_name,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country_name' => $this->country_name,
            'full_address' => $this->full_address,
            'email' => $this->email,
            'phone' => $this->phone,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }
}
