<?php

namespace App\Http\Controllers\Collaboration\API;

use App\Models\Collaboration\CalendarEvent;
use App\Models\Collaboration\CalendarResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collaboration\GetCalendarEvents;
use App\Http\Requests\Collaboration\StoreCalendarEvent;
use App\Http\Requests\Collaboration\UpdateCalendarEvent;
use App\Http\Requests\Collaboration\UpdateCalendarEventDate;
use App\Http\Resources\Collaboration\CalendarEventResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class CalendarEventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CalendarEvent::class, 'event');
        CalendarEventResource::withoutWrapping();
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), ['updateDate' => 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Collaboration\GetCalendarEvents  $request
     * @return \Illuminate\Http\Response
     */
    public function index(GetCalendarEvents $request)
    {
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
     * @param  \App\Http\Requests\Collaboration\StoreCalendarEvent  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCalendarEvent $request)
    {
        $event = new CalendarEvent();
        $event->title = $request->title;
        $event->description = $request->description;
        self::parseDate($request, $event);
        $event->resource()->associate(CalendarResource::find($request->resourceId));
        $event->user()->associate(Auth::user());
        $event->save();
        return (new CalendarEventResource($event))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collaboration\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarEvent $event)
    {
        return new CalendarEventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Collaboration\UpdateCalendarEvent  $request
     * @param  \App\Models\Collaboration\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCalendarEvent $request, CalendarEvent $event)
    {
        $event->title = $request->title;
        $event->description = !empty($request->description) ? $request->description : null;
        self::parseResourceId($request, $event);
        $event->save();
        return response()->json([], 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Collaboration\UpdateCalendarEventDate  $request
     * @param  \App\Models\Collaboration\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function updateDate(UpdateCalendarEventDate $request, CalendarEvent $event)
    {
        self::parseDate($request, $event);
        self::parseResourceId($request, $event);
        $event->save();
        return response()->json([], 204);
    }

    private static function parseDate(Request $request, CalendarEvent $event)
    {
        $startDate = new Carbon($request->start);
        $endDate = $request->end != null ? new Carbon($request->end) : (clone $startDate)->addDay()->startOfDay();
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->all_day = $startDate->toTimeString() == '00:00:00' && $endDate->toTimeString() == '00:00:00';
    }

    private static function parseResourceId(Request $request, CalendarEvent $event)
    {
        if ($request->resourceId != null && $request->resourceId != $event->resource->id) {
            $event->resource()->dissociate();
            $event->resource()->associate(CalendarResource::find($request->resourceId));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collaboration\CalendarEvent  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEvent $event)
    {
        $event->delete();
        return response()->json([], 204);
    }
}
