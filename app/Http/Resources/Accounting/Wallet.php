<?php

namespace App\Http\Resources\Accounting;

use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;

class Wallet extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $amount = $this->amount;
        $data = parent::toArray($request);
        $data['amount'] = $amount;
        $data['amount_formatted'] = $this->formatCurrency($amount);
        $data['num_transactions'] = $this->transactions()->count();
        $data['is_restricted'] = $this->roles()->exists();
        $data['roles'] = $this->roles->map(fn ($u) => $u->only(['id', 'name']));
        $data['latest_activity'] = $this->latestActivity;
        $data['next_free_receipt'] = $this->nextFreeReceiptNumber;
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);
        return $data;
    }
}
