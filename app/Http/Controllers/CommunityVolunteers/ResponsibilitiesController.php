<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityVolunteers\StoreResponsibility;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\Response;

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

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('cmtyvol.responsibilities.index', [
            'responsibilities' => Responsibility::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('cmtyvol.responsibilities.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreResponsibility $request
     * @return Response
     */
    public function store(StoreResponsibility $request)
    {
        $responsibility = new Responsibility();
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
        $responsibility->save();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('success', __('responsibilities.responsibility_added'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Responsibility $responsibility
     * @return Response
     */
    public function edit(Responsibility $responsibility)
    {
        return view('cmtyvol.responsibilities.edit', [
            'responsibility' => $responsibility,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param StoreResponsibility $request
     * @param Responsibility $responsibility
     * @return Response
     */
    public function update(StoreResponsibility $request, Responsibility $responsibility)
    {
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
        $responsibility->save();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('info', __('responsibilities.responsibility_updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Responsibility $responsibility
     * @return Response
     */
    public function destroy(Responsibility $responsibility)
    {
        $responsibility->delete();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('success', __('responsibilities.responsibility_deleted'));
    }
}
