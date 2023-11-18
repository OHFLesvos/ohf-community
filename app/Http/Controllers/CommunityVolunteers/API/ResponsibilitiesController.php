<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityVolunteers\StoreResponsibility;
use App\Http\Resources\CommunityVolunteers\Responsibility as ResponsibilityResource;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponsibilitiesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Responsibility::class);
    }

    public function index(): JsonResource
    {
        return ResponsibilityResource::collection(Responsibility::orderBy('name')->get());
    }

    public function store(StoreResponsibility $request): JsonResponse
    {
        $responsibility = new Responsibility();
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available') && $request->input('available') == true;
        $responsibility->save();

        return response()->json([
            'id' => $responsibility->id,
            'message' => __('Responsibility added.'),
        ]);
    }

    public function show(Responsibility $responsibility): JsonResource
    {
        return new ResponsibilityResource($responsibility);
    }

    public function update(StoreResponsibility $request, Responsibility $responsibility): JsonResponse
    {
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available') && $request->input('available') == true;
        $responsibility->save();

        return response()->json([
            'message' => __('Responsibility updated.'),
        ]);
    }

    public function destroy(Responsibility $responsibility): JsonResponse
    {
        $responsibility->delete();

        return response()->json([
            'message' => __('Responsibility deleted.'),
        ]);
    }
}
