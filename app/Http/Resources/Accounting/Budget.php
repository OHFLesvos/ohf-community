<?php

namespace App\Http\Resources\Accounting;

use App\Http\Resources\Fundraising\Donor;
use Illuminate\Http\Resources\Json\JsonResource;

class Budget extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "agreed_amount" => (float) $this->agreed_amount,
            "initial_amount" => (float) $this->initial_amount,
            "donor_id" => $this->donor_id,
            'donor' => new Donor($this->whenLoaded('donor')),
            'balance' => $this->getBalance(),
            'num_transactions' => $this->transactions->count(),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            "is_completed" => $this->is_completed,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "closed_at" => $this->closed_at,
        ];
    }
}
