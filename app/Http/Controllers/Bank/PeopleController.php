<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\People\Person;
use Countries;

class PeopleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Person::class);
    }

    public function create()
    {
        return view('bank.people.create', [
            'countries' => array_values(Countries::getList('en')),
        ]);
    }

    public function show(Person $person)
    {
        return view('bank.people.show', [
            'person' => $person,
        ]);
    }

    public function edit(Person $person)
    {
        return view('bank.people.edit', [
            'person' => $person,
            'countries' => array_values(Countries::getList('en')),
        ]);
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()
            ->route('bank.withdrawal.search')
            ->with('success', __('people.person_deleted'));
    }
}
