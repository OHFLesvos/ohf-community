<?php

namespace App\Http\Resources\Accounting;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \OwenIt\Auditing\Models\Audit
 */
class TransactionHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->auditable_id,
            'event' => $this->event,
            'changes' => $this->getChanges($this->old_values, $this->new_values),
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at,
            'user' => $this->getUserArray($this->user),
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
            if (! isset($changes[$key]['old'])) {
                $changes[$key]['old'] = null;
            }
            $changes[$key]['new'] = $val;
        }

        return collect($changes)
            ->sortKeys()
            ->toArray();
    }

    private function getUserArray(?User $user): ?array
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
