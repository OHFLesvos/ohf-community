<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'update_url' => Auth::user()->can('update', $this->resource)
                ? route('api.comments.update', $this->resource)
                : null,
            'delete_url' => Auth::user()->can('delete', $this->resource)
                ? route('api.comments.update', $this->resource)
                : null,
        ];
    }
}
