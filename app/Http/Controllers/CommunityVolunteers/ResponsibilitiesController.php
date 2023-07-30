<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityVolunteers\StoreResponsibility;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

    public function index(): View
    {
        return view('cmtyvol.responsibilities.index', [
            'responsibilities' => Responsibility::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('cmtyvol.responsibilities.create');
    }

    public function store(StoreResponsibility $request): RedirectResponse
    {
        $responsibility = new Responsibility();
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
        $responsibility->save();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('success', __('Responsibility added.'));
    }

    public function edit(Responsibility $responsibility): View
    {
        return view('cmtyvol.responsibilities.edit', [
            'responsibility' => $responsibility,
        ]);
    }

    public function update(StoreResponsibility $request, Responsibility $responsibility): RedirectResponse
    {
        $responsibility->fill($request->all());
        $responsibility->available = $request->has('available');
        $responsibility->save();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('info', __('Responsibility updated.'));
    }

    public function destroy(Responsibility $responsibility): RedirectResponse
    {
        $responsibility->delete();

        return redirect()->route('cmtyvol.responsibilities.index')
            ->with('success', __('Responsibility deleted.'));
    }
}
