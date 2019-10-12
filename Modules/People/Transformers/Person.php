<?php

namespace Modules\People\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class Person extends Resource
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
        $data['police_no'] = $this->police_no_formatted;
        $data['languages'] = $this->languages_string;
        $data['url'] = Auth::user()->can('view', $this->resource) ? route('people.show', $this) : null;
        return $data;
    }
}
