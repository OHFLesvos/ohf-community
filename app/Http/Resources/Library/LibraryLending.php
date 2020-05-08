<?php

namespace App\Http\Resources\Library;

use App\Http\Resources\Library\LibraryBook as LibraryBookResource;
use App\Http\Resources\People\Person as PersonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LibraryLending extends JsonResource
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
            'id' => $this->id,
            'book' => $this->whenLoaded('book',fn () => new LibraryBookResource($this->book)),
            'person' => $this->whenLoaded('person',fn () => new PersonResource($this->person)),
            'lending_date' => $this->lending_date,
            'return_date' => $this->return_date,
            'returned_date' => $this->returned_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
