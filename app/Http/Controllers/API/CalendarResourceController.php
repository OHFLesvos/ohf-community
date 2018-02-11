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
        $type = new CalendarEventTypeResource();
        $type->name = $request->title;
        $type->color = $request->color;
        $type->save();
        return (new CalendarEventTypeResource($type))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CalendarEventType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarEventType $type)
    {
        return new CalendarEventTypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CalendarEventType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalendarEventType $type)
    {
        $type->name = $request->title;
        $type->color = $request->color;
        $type->save();
        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CalendarEventType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEventType $type)
    {
        $type->delete();
        return response()->json([], 204);
    }
}
