<?php

namespace App\Http\Controllers\API;

use App\CalendarResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarResourceResource;
use App\Http\Requests\Calendar\StoreResource;
use App\Http\Requests\Calendar\UpdateResource;

class CalendarResourceController extends Controller
{
    public function __construct() {
        CalendarResourceResource::withoutWrapping();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarResourceResource::collection(CalendarResource::orderBy('title')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Calendar\StoreResource  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResource $request)
    {
        $resource = new CalendarResource();
        $resource->title = $request->title;
        $resource->color = $request->color;
        $resource->default = CalendarResource::count() == 0;
        $resource->save();
        return (new CalendarResourceResource($resource))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarResource $resource)
    {
        return new CalendarResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Calendar\UpdateResource  $request
     * @param  \App\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResource $request, CalendarResource $resource)
    {
        $resource->title = $request->title;
        $resource->color = $request->color;
        // TODO default
        $resource->save();
        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarResource $resource)
    {
        $resource->delete();
        return response()->json([123], 204);
    }
}
