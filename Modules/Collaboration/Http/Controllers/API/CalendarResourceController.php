<?php

namespace Modules\Collaboration\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Collaboration\Entities\CalendarResource;
use Modules\Collaboration\Http\Requests\StoreResource;
use Modules\Collaboration\Http\Requests\UpdateResource;
use Modules\Collaboration\Transformers\CalendarResourceResource;

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
        $this->authorize('list', CalendarResource::class);

        return CalendarResourceResource::collection(CalendarResource::orderBy('title')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Collaboration\Http\Requests\StoreResource  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResource $request)
    {
        $this->authorize('create', CalendarResource::class);

        $resource = new CalendarResource();
        $resource->title = $request->title;
        $resource->color = $request->color;
        $resource->group = !empty($request->group) ? $request->group : null;
        $resource->default = CalendarResource::count() == 0;
        $resource->save();
        return (new CalendarResourceResource($resource))->response(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Modules\Collaboration\Entities\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarResource $resource)
    {
        $this->authorize('view', $resource);

        return new CalendarResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Collaboration\Http\Requests\UpdateResource  $request
     * @param  \Modules\Collaboration\Entities\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResource $request, CalendarResource $resource)
    {
        $this->authorize('update', $resource);

        $resource->title = $request->title;
        $resource->color = $request->color;
        // TODO default
        $resource->group = !empty($request->group) ? $request->group : null;
        $resource->save();
        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Collaboration\Entities\CalendarResource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarResource $resource)
    {
        $this->authorize('delete', $resource);

        $resource->delete();
        return response()->json([123], 204);
    }
}
