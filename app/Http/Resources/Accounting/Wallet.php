<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class Wallet extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['amount'] = $this->amount;
        $data['num_transactions'] = $this->transactions()->count();
        $data['is_restricted'] = $this->roles()->exists();
        $data['latest_activity'] = $this->latestActivity;
        return $data;
    }
}
