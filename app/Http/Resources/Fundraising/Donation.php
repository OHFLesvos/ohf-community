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
        $amount = $this->amount;
        if ($this->currency != config('fundraising.base_currency')) {
            $amount .= ' ('. config('fundraising.base_currency') . ' '. $this->exchange_amount;
        }
        return [
            'date' => $this->date,
            'amount' => $amount,
            'donor' => $this->donor->full_name,
            'channel' => $this->channel,
            'purpose' => $this->purpose,
            'reference' => $this->reference,
            'in_name_of' => $this->in_name_of,
            'registered' => $this->created_at,
            'thanked' => optional($this->thanked)->toDateString()
        ];
    }
}
