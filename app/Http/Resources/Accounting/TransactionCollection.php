<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Setting;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
