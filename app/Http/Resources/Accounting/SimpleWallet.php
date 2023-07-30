<?php

namespace App\Http\Resources\Accounting;

use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Accounting\Wallet
 */
class SimpleWallet extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $amount,
            'amount_formatted' => $this->formatCurrency($amount),
        ];
    }
}
