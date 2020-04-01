<?php

namespace App\Http\Resources\Collaboration;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CalendarEventResource extends JsonResource
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
            'allDay' => (bool) $this->all_day,
            'resourceId' => $this->resource->resource->id,
            'user' => new UserResource($this->user),
            'editable' => Auth::user()->can('update', $this->resource),
            'url' => route('calendar.events.show', $this),
            'updateDateUrl' => route('calendar.events.updateDate', $this),
        ];
    }
}
