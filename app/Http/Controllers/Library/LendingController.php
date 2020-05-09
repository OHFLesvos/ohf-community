<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\People\Person;

class LendingController extends Controller
{
    public function person(Person $person)
    {
        $this->authorize('viewAny', Person::class);

        return view('library.lending.person', [
            'person' => $person,
        ]);
    }

    public function book(LibraryBook $book)
    {
        $this->authorize('view', $book);

        return view('library.lending.book', [
            'book' => $book,
        ]);
    }
}
