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
        $data['roles'] = $this->roles->map(fn ($u) => $u->only(['id', 'name']));
        $data['latest_activity'] = $this->latestActivity;
        $data['next_free_receipt'] = $this->nextFreeReceiptNumber;
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);
        return $data;
    }
}
