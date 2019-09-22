<?php

namespace Modules\School\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\School\Entities\SchoolClass;
use Modules\School\Http\Requests\StoreClass;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Carbon\Carbon;

class SchoolClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('list', SchoolClass::class);

        $today = Carbon::today();

        $activeClasses = SchoolClass::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->orderBy('name')->get();
        $futureClasses = SchoolClass::whereDate('start_date', '>', $today)->orderBy('start_date')->orderBy('name')->get();
        $pastClasses = SchoolClass::whereDate('end_date', '<', $today)->orderBy('end_date', 'desc')->orderBy('name')->get();

        return view('school::classes.index', [
            'schoolClassCategories' => [
                'active' => [
                    'label' => 'Active classes',
                    'classes' => $activeClasses,
                ],
                'future' => [
                    'label' => 'Future classes',
                    'classes' => $futureClasses,
                ],
                'past' => [
                    'label' => 'Past classes',
                    'classes' => $pastClasses,
                ],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', SchoolClass::class);

        return view('school::classes.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreClass $request
     * @return Response
     */
    public function store(StoreClass $request)
    {
        $this->authorize('create', SchoolClass::class);

        $class = new SchoolClass();
        $class->fill($request->all());
        $class->save();

        return redirect()
            ->route('school.classes.index')
            ->with('success', __('school::classes.class_created'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param SchoolClass $class
     * @return Response
     */
    public function edit(SchoolClass $class)
    {
        $this->authorize('update', $class);

        return view('school::classes.edit', [
            'class' => $class
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param StoreClass $request
     * @param SchoolClass $schoolClass
     * @return Response
     */
    public function update(StoreClass $request, SchoolClass $class)
    {
        $this->authorize('update', $class);

        $class->fill($request->all());
        $class->save();

        return redirect()
            ->route('school.classes.index')
            ->with('success', __('school::classes.class_updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param SchoolClass $schoolClass
     * @return Response
     */
    public function destroy(SchoolClass $class)
    {
        $this->authorize('delete', $class);

        $class->delete();

        return redirect()
            ->route('school.classes.index')
            ->with('success', __('school::classes.class_deleted'));
    }
}
