<?php

namespace App\Http\Resources\Accounting;

use App\Http\Resources\Fundraising\Donor;
use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Accounting\Budget
 */
class Budget extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $balance = $this->getBalance();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'agreed_amount' => (float) $this->agreed_amount,
            'agreed_amount_formatted' => $this->formatCurrency($this->agreed_amount),
            'initial_amount' => (float) $this->initial_amount,
            'initial_amount_formatted' => $this->formatCurrency($this->initial_amount),
            'donor_id' => $this->donor_id,
            'donor' => new Donor($this->whenLoaded('donor')),
            'balance' => $balance,
            'balance_formatted' => $this->formatCurrency($balance),
            'num_transactions' => $this->transactions->count(),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'closed_at' => $this->closed_at,
        ];
    }
}
