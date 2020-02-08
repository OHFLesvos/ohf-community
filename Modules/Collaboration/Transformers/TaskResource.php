<?php

namespace Modules\Collaboration\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TaskResource extends Resource
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
            'description' => $this->description,
            'date' => $this->created_at->toDateString(),
        ];
    }
}
