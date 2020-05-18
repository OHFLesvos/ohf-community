<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
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
            'content' => $this->content,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'user_url' => $this->user_id !== null && $request->user()->can('view', $this->user)
                ? route('users.show', $this->user)
                : null,
        ];
    }
}
