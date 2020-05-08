<?php

namespace App\Http\Resources\Library;

use Illuminate\Http\Resources\Json\JsonResource;

class Borrower extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'public_id' => $this->public_id,
            'full_name' => $this->fullName,
            'lendings_overdue' => $this->hasOverdueBookLendings,
            'lendings_count' => $this->bookLendings()
                ->whereNull('returned_date')
                ->count(),
        ];
    }
}
