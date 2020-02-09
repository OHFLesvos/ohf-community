<?php

namespace App\Http\Resources\Bank;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BankPersonCollection extends ResourceCollection
{
    protected $couponTypes = [];

    public function withCouponTypes($couponTypes) {
        $this->couponTypes = $couponTypes;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function(BankPerson $resource) use($request){
                return $resource->withCouponTypes($this->couponTypes)->toArray($request);
        })->all();
    }
}
