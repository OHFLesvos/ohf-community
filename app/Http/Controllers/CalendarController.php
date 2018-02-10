<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarEvent;
use App\CalendarEventType;
use App\Http\Resources\CalendarEventResource;
use Carbon\Carbon;
use  App\Http\Requests\GetCalendarEvents;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function __construct() {
        CalendarEventResource::withoutWrapping();
    }

    public function index() {
        return view('calendar.index');
    }

    public function listEvents(GetCalendarEvents $request) {
        $startDate = new Carbon($request->start, $request->timezone);
        $endDate = new Carbon($request->end, $request->timezone);
        $data = CalendarEvent::whereDate('start_date', '>=', $startDate)->whereDate('end_date', '<=', $endDate)->get();
        return CalendarEventResource::collection($data);
    }    

    public function showEvent(CalendarEvent $event) {
        return new CalendarEventResource($event);
    }

    public function storeEvent(Request $request) {
        $event = new CalendarEvent();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->start_date = $request->start;
        $event->end_date = $request->end;
        $event->all_day = (new Carbon($request->start))->toTimeString() == '00:00:00' && (new Carbon($request->end))->toTimeString() == '00:00:00';
        $type = CalendarEventType::find(1); // TODO
        $event->type()->associate($type);
        $event->user()->associate(Auth::user());
        $event->save();
        return (new CalendarEventResource($event))->response(201);
    }

    public function updateEvent(CalendarEvent $event, Request $request) {
        $startDate = new Carbon($request->start);
        $endDate = $request->end != null ? $request->end : (clone $startDate)->addDay()->startOfDay();
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->all_day = $startDate->toTimeString() == '00:00:00' && $endDate->toTimeString() == '00:00:00';
        $event->save();
        return response()->json([], 204);
    }
}
