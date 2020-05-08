<?php

namespace App\Http\Resources\Library;

use Illuminate\Http\Resources\Json\JsonResource;

class LentBook extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lending = $this->lendings()
            ->whereNull('returned_date')
            ->first();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'lending' => [
                'return_date' => $lending->return_date,
                'person' => $lending->person ? [
                    'public_id' => $lending->person->public_id,
                    'full_name' => $lending->person->fullName,
                ] : null,
            ]
        ];
    }
}
