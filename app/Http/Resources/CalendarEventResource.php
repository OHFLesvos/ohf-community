<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

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
            'user' => new UserResource($this->user),
            'editable' => Auth::user()->can('update', $this->resource),
            'url' => route('calendar.events.show', $this),
            'updateUrl' => route('calendar.events.update', $this),
            'updateDateUrl' => route('calendar.events.updateDate', $this),
            'deleteUrl' => route('calendar.events.destroy', $this),
        ];
    }
}
