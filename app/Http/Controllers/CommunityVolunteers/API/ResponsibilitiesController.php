<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityVolunteers\StoreResponsibility;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\JsonResponse;

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

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Responsibility::orderBy('name')->get(),
        ]);
    }

    public function store(StoreResponsibility $request): JsonResponse
    {
        $responsibility = new Responsibility();
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
        $responsibility->save();

        return response()->json([
            'id' => $responsibility->id,
            'message' => __('Responsibility added.'),
        ]);
    }

    public function show(Responsibility $responsibility): JsonResponse
    {
        return response()->json([
            'data' => $responsibility,
        ]);
    }

    public function update(StoreResponsibility $request, Responsibility $responsibility): JsonResponse
    {
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
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
