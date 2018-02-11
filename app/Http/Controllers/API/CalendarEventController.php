<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\GetCalendarEvents;
use App\Http\Requests\StoreCalendarEvent;
use App\Http\Requests\UpdateCalendarEvent;
use App\Http\Requests\UpdateCalendarEventDate;
use App\CalendarEvent;
use App\CalendarEventType;
use App\Http\Resources\CalendarEventResource;

class CalendarEventController extends Controller
{
    public function __construct() {
        // TODO authorizeResource seems not to work properly, therefore using explicit approach in each method
        //$this->authorizeResource(CalendarEvent::class);
        CalendarEventResource::withoutWrapping();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\GetCalendarEvents  $request
     * @return \Illuminate\Http\Response
     */
    public function index(GetCalendarEvents $request)
    {
        $this->authorize('list', CalendarEvent::class);

        $qry = CalendarEvent::with('user');
        if ($request->start != null) {
            $qry->whereDate('start_date', '>=', new Carbon($request->start, $request->timezone));
            if ($request->end != null) {
                $qry->whereDate('end_date', '<=', new Carbon($request->end, $request->timezone));
            }
        }
        return CalendarEventResource::collection($qry->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCalendarEvent  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCalendarEvent $request)
    {
        $this->authorize('create', CalendarEvent::class);

        $event = new CalendarEvent();
        $event->title = $request->title;
        $event->description = $request->description;
        self::parseDate($request, $event);
        $type = CalendarEventType::find($request->type);
        $event->type()->associate($type);
        $event->user()->associate(Auth::user());
        $event->save();
        return (new CalendarEventResource($event))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarEvent $event)
    {
        $this->authorize('view', $event);

        return new CalendarEventResource($event);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCalendarEvent  $request
     * @param  \App\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCalendarEvent $request, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $event->title = $request->title;
        $event->description = !empty($request->description) ? $request->description : null;
        if ($request->type != null && $request->type != $event->type->id) {
            $type = CalendarEventType::find($request->type);
            $event->type()->dissociate();
            $event->type()->associate($type);
        }
        $event->save();
        return response()->json([], 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCalendarEventDate  $request
     * @param  \App\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function updateDate(UpdateCalendarEventDate $request, CalendarEvent $event) {
        $this->authorize('update', $event);

        self::parseDate($request, $event);
        $event->save();
        return response()->json([], 204);
    }

    private static function parseDate(Request $request, CalendarEvent $event) {
        $startDate = new Carbon($request->start);
        $endDate = $request->end != null ? new Carbon($request->end) : (clone $startDate)->addDay()->startOfDay();
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->all_day = $startDate->toTimeString() == '00:00:00' && $endDate->toTimeString() == '00:00:00';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEvent $event)
    {
        $this->authorize('delete', $event);

        $event->delete();
        return response()->json([], 204);
    }
}
