<?php

namespace App\Http\Resources\Accounting;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistory extends JsonResource
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
            "event" => $this->event,
            "old_values" => $this->old_values,
            "new_values" => $this->new_values,
            "ip_address" => $this->ip_address,
            "user_agent" => $this->user_agent,
            "created_at" => $this->created_at,
            "user"  => $this->getUserArray($this->user),
        ];
    }

    private function getUserArray(?User $user)
    {
        if ($user === null) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
