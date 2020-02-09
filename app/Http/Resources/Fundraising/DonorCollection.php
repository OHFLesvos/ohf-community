<?php

namespace App\Http\Resources\Fundraising;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DonorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
