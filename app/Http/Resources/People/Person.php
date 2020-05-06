<?php

namespace App\Http\Resources\People;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Person extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['full_name'] = $this->fullName;
        $data['police_no_formatted'] = $this->police_no_formatted;
        $data['languages'] = $this->languages_string;
        $data['url'] = Auth::user()->can('view', $this->resource) ? route('people.show', $this) : null;
        return $data;
    }
}
