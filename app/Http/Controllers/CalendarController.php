<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarEvent;
use App\Http\Resources\CalendarEventResource;
use Carbon\Carbon;
use  App\Http\Requests\GetCalendarEvents;

class CalendarController extends Controller
{
    public function index() {
        return view('calendar.index');
    }

    public function listEvents(GetCalendarEvents $request) {
        $startDate = new Carbon($request->start, $request->timezone);
        $endDate = new Carbon($request->end, $request->timezone);
        $data = CalendarEvent::whereDate('start_date', '>=', $startDate)->whereDate('end_date', '<=', $endDate)->get();
        CalendarEventResource::withoutWrapping();
        return CalendarEventResource::collection($data);
    }    

    public function showEvent(CalendarEvent $event) {
        return new CalendarEventResource($event);
    }

    public function updateEvent(CalendarEvent $event, Request $request) {
        $event->start_date = $request->start;
        $event->end_date = $request->end;
        $event->save();
        return response()->json([], 204);
    }
}
