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
            "changes" => $this->getChanges($this->old_values, $this->new_values),
            "ip_address" => $this->ip_address,
            "created_at" => $this->created_at,
            "user"  => $this->getUserArray($this->user),
        ];
    }

    private function getChanges(array $oldValues, array $newValues): array
    {
        $changes = [];
        foreach ($oldValues as $key => $val) {
            $changes[$key]['old'] = $val;
            $changes[$key]['new'] = null;
        }
        foreach ($newValues as $key => $val) {
            if (!isset($changes[$key]['old'])) {
                $changes[$key]['old'] = null;
            }
            $changes[$key]['new'] = $val;
        }

        return collect($changes)
            ->filter(fn ($val, $key) => $key != "id")
            ->sortKeys()
            ->toArray();
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
