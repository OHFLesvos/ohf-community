<?php

namespace App\Http\Resources\Fundraising;

use Illuminate\Http\Resources\Json\JsonResource;

class Donor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->country_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
