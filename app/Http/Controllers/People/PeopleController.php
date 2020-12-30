<?php

namespace App\Http\Controllers\People;

use App\Exports\People\PeopleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\StorePerson;
use App\Http\Requests\UploadSpreadsheet;
use App\Imports\People\PeopleImport;
use App\Models\People\Person;
use Carbon\Carbon;
use Countries;

class PeopleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class);
    }

    public function index()
    {
        return view('people.index');
    }

    public function create()
    {
        return view('people.create', [
            'countries' => array_values(Countries::getList('en')),
        ]);
    }

    public function store(StorePerson $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->save();

        return redirect()
            ->route('people.index')
            ->with('success', __('people.person_added'));
    }

    public function show(Person $person)
    {
        return view('people.show', [
            'person' => $person,
        ]);
    }

    public function edit(Person $person)
    {
        return view('people.edit', [
            'person' => $person,
            'countries' => array_values(Countries::getList('en')),
        ]);
    }

    public function update(StorePerson $request, Person $person)
    {
        $person->fill($request->all());
        $person->save();
        return redirect()->route('people.show', $person)
            ->with('success', __('people.person_updated'));
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('people.index')
            ->with('success', __('people.person_deleted'));
    }

    public function export()
    {
        $file_name = __('people.people') . ' ' . Carbon::now()->toDateString();
        $extension = 'xlsx';
        return (new PeopleExport())->download($file_name . '.' . $extension);
    }

    public function import(UploadSpreadsheet $request)
    {
        $import = new PeopleImport();
        $import->import($request->file('file'));

        return redirect()->route('people.index')
            ->with('success', __('app.imported_num_records', ['num' => $import->count()]));
    }
}
