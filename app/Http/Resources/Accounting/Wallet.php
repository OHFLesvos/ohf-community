<?php

namespace App\Http\Resources\Accounting;

use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Accounting\Wallet
 */
class Wallet extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $amount = $this->amount;

        return [
            ...parent::toArray($request),
            'amount' => $amount,
            'amount_formatted' => $this->formatCurrency($amount),
            'num_transactions' => $this->transactions()->count(),
            'is_restricted' => $this->roles()->exists(),
            'roles' => $this->roles->map(fn ($u) => $u->only(['id', 'name'])),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }
}
