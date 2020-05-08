<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreExtendBook;
use App\Http\Requests\Library\StoreLendBook;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use App\Settings\Library\DefaultLendingDurationDays;
use Carbon\Carbon;
use Setting;

class LendingController extends Controller
{
    public function persons()
    {
        $this->authorize('list', Person::class);

        $persons = Person::query()
            ->whereHas('bookLendings', function ($query) {
                $query->whereNull('returned_date');
            })
            ->get()
            ->sortBy('fullName');

        return view('library.lending.persons', [
            'persons' => $persons,
        ]);
    }

    public function person(Person $person)
    {
        $this->authorize('list', Person::class);

        return view('library.lending.person', [
            'person' => $person,
        ]);
    }

    public function personLog(Person $person)
    {
        $this->authorize('list', Person::class);

        return view('library.lending.personLog', [
            'person' => $person,
            'lendings' => $person->bookLendings()->orderBy('lending_date', 'desc')->paginate(25),
        ]);
    }

    public function books()
    {
        $this->authorize('list', LibraryBook::class);

        return view('library.lending.books', [
            'books' => LibraryBook::whereHas('lendings', function ($query) {
                $query->whereNull('returned_date');
            })->get()->sortBy('title'),
        ]);
    }

    public function book(LibraryBook $book)
    {
        $this->authorize('view', $book);

        return view('library.lending.book', [
            'book' => $book,
        ]);
    }

    public function bookLog(LibraryBook $book)
    {
        $this->authorize('view', $book);

        return view('library.lending.bookLog', [
            'book' => $book,
            'lendings' => $book->lendings()->orderBy('lending_date', 'desc')->paginate(25),
        ]);
    }
}
