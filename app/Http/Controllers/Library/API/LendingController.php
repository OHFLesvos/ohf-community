<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Http\Resources\Library\LibraryLending as LibraryLendingResource;
use App\Models\People\Person;
use Setting;

class LendingController extends Controller
{
    /**
     * Gets core stats about lent books and borrowers
     *
     * @return \Illuminate\Http\Response
     */
    public function stats()
    {
        return [
            'num_borrowers' => Person::query()
                ->whereHas('bookLendings', function ($query) {
                    $query->whereNull('returned_date');
                })
                ->count(),
            'num_lent_books' => LibraryBook::query()
                ->whereHas('lendings', function ($query) {
                    $query->whereNull('returned_date');
                })
                ->count(),
        ];
    }

    public function person(Person $person)
    {
        $this->authorize('list', Person::class);

        $lendings = $person->bookLendings()
            ->whereNull('returned_date')
            ->orderBy('return_date', 'asc')
            ->get();
        $can_lend = ! Setting::has('library.max_books_per_person') || Setting::get('library.max_books_per_person') > $lendings->count();

        return LibraryLendingResource::collection($lendings)
            ->additional(['meta' => [
                'can_lend' => $can_lend,
            ]]);;
    }
}
