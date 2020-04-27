<?php

namespace App\Http\Resources\Fundraising;

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
            'date' => $this->date,
            'amount' => $this->amount,
            'exchange_amount' => $this->exchange_amount,
            'currency' => $this->currency,
            'base_currency' => config('fundraising.base_currency'),
            'donor' => $this->donor->full_name,
            'channel' => $this->channel,
            'purpose' => $this->purpose,
            'reference' => $this->reference,
            'in_name_of' => $this->in_name_of,
            'created_at' => $this->created_at,
            'thanked' => optional($this->thanked)->toDateString(),
            'edit_url' => route('fundraising.donations.edit', [$this->donor, $this->resource]),
            'donor_url' => route('fundraising.donors.show', $this->donor),
        ];
    }
}
