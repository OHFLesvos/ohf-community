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

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'use_locations' => (bool) Setting::get('accounting.transactions.use_locations') ?? false,
                'use_secondary_categories' => (bool) Setting::get('accounting.transactions.use_secondary_categories') ?? false,
                'use_cost_centers' => (bool) Setting::get('accounting.transactions.use_cost_centers') ?? false,
            ],
        ];
    }
}
