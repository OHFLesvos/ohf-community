<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\GetCalendarEvents;
use App\Http\Requests\StoreCalendarEvent;
use App\CalendarEvent;
use App\CalendarEventType;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\CalendarEventTypeResource;

class CalendarController extends Controller
{
    public function __construct() {
        CalendarEventResource::withoutWrapping();
    }

    public function index() {
        return view('calendar.index', [
            'types' => CalendarEventType::orderBy('name')
                ->get()
                ->mapWithKeys(function($e){
                    return [$e->id => $e->name];
                })
                ->toArray(),
        ]);
    }

    public function listEventTypes() {
        return CalendarEventTypeResource::collection(CalendarEventType::orderBy('name')->get());
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

    public function storeEvent(StoreCalendarEvent $request) {
        $event = new CalendarEvent();
        $event->title = $request->title;
        $event->description = $request->description;
        self::parseDate($event, $request);
        $type = CalendarEventType::find($request->type);
        $event->type()->associate($type);
        $event->user()->associate(Auth::user());
        $event->save();
        return (new CalendarEventResource($event))->response(201);
    }

    // TODO validation
    public function updateEvent(CalendarEvent $event, Request $request) {
        if ($request->start != null) {
            self::parseDate($event, $request);
        }
        if (!empty($request->title)) {
            $event->title = $request->title;
        }
        if ($request->description !== null) {
            $event->description = !empty($request->description) ? $request->description : null;
        }
        if ($request->type != null && $request->type != $event->type->id) {
            $type = CalendarEventType::find($request->type);
            $event->type()->dissociate();
            $event->type()->associate($type);
        }
        $event->save();
        return response()->json([], 204);
    }

    private static function parseDate(CalendarEvent $event, Request $request) {
        $startDate = new Carbon($request->start);
        $endDate = $request->end != null ? new Carbon($request->end) : (clone $startDate)->addDay()->startOfDay();
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->all_day = $startDate->toTimeString() == '00:00:00' && $endDate->toTimeString() == '00:00:00';
    }
}
