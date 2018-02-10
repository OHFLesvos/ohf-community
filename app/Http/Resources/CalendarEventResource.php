<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class CalendarEventResource extends Resource
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
            'start' => (new Carbon($this->start_date))->toIso8601String(),
            'end' => (new Carbon($this->end_date))->toIso8601String(),
            'allDay' => (bool)$this->all_day,
            'color' => $this->type->color,
        ];
    }
}
