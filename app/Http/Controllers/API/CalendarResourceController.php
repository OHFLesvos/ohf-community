<?php

namespace App\Http\Controllers\API;

use App\CalendarEventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventTypeResource;

class CalendarResourceController extends Controller
{
    public function __construct() {
        CalendarEventTypeResource::withoutWrapping();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarEventTypeResource::collection(CalendarEventType::orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendarEventType = new CalendarEventType();
        $calendarEventType->name = $request->title;
        $calendarEventType->color = $request->color;
        $calendarEventType->save();
        return (new CalendarEventTypeResource($calendarEventType))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CalendarEventType  $calendarEventType
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarEventType $calendarEventType)
    {
        return new CalendarEventTypeResource($calendarEventType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CalendarEventType  $calendarEventType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalendarEventType $calendarEventType)
    {
        $calendarEventType->name = $request->title;
        $calendarEventType->color = $request->color;
        $calendarEventTypetype->save();
        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CalendarEventType  $calendarEventType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEventType $calendarEventType)
    {
        $calendarEventType->delete();
        return response()->json([123], 204);
    }
}
