<?php

namespace Modules\Fundraising\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class Donor extends Resource
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
            'tags' => $this->tags->pluck('name'),
            'url' => route('fundraising.donors.show', $this),
        ];
    }
}
