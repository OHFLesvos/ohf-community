<?php

namespace App\Http\Resources\Library;

use Illuminate\Http\Resources\Json\JsonResource;

class LibraryBook extends JsonResource
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
            'title' => $this->title,
            'author' => $this->author,
            'language_code' => $this->language_code,
            'language' => $this->language,
            'isbn' => $this->isbn,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
