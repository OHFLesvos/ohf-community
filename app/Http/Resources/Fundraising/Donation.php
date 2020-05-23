<?php

namespace App\Http\Resources\Fundraising;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Donation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'amount' => $this->amount,
            'exchange_amount' => $this->exchange_amount,
            'currency' => $this->currency,
            'donor_id' => $this->whenLoaded('donor', fn () => $this->donor->id),
            'donor' => $this->whenLoaded('donor', fn () => $this->donor->full_name),
            'channel' => $this->channel,
            'purpose' => $this->purpose,
            'reference' => $this->reference,
            'in_name_of' => $this->in_name_of,
            'thanked' => $this->thanked,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
