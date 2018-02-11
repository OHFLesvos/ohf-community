<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
            'description' => $this->description,
            'start' => (new Carbon($this->start_date))->toIso8601String(),
            'end' => (new Carbon($this->end_date))->toIso8601String(),
            'allDay' => (bool)$this->all_day,
            'color' => $this->type->color,
            'type' => $this->type->id,
            'updateUrl' => route('calendar.updateEvent', $this),
            'updateDateUrl' => route('calendar.updateEventDate', $this),
            'deleteUrl' => route('calendar.deleteEvent', $this),
            'editable' => true,
            'user' => Auth::user()->name,
        ];
    }
}
