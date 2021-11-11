<?php

namespace App\Http\Resources\Accounting;

use App\Http\Resources\Fundraising\Donor;
use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class Budget extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $balance = $this->getBalance();
        $donor = new Donor($this->whenLoaded('donor'));
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "agreed_amount" => (float) $this->agreed_amount,
            "agreed_amount_formatted" => $this->formatCurrency($this->agreed_amount),
            "initial_amount" => (float) $this->initial_amount,
            "initial_amount_formatted" =>  $this->formatCurrency($this->initial_amount),
            "donor_id" => $this->donor_id,
            'donor' => (optional($request->user())->can('view', $donor->resource) ?? false) ? $donor : null,
            'balance' => $balance,
            'balance_formatted' => $this->formatCurrency($balance),
            'num_transactions' => $this->transactions->count(),
            'can_update' => optional($request->user())->can('update', $this->resource) ?? false,
            'can_delete' => optional($request->user())->can('delete', $this->resource) ?? false,
            "is_completed" => $this->is_completed,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "closed_at" => $this->closed_at,
            'public_url' => URL::signedRoute('api.accounting.budgets.show-public', ['budget' => $this->id]),
        ];
    }
}
