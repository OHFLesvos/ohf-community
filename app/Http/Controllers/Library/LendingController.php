<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LibraryLending;
use App\LibraryBook;
use App\Person;
use Carbon\Carbon;
use App\Http\Requests\Library\StoreLendBook;
use App\Http\Requests\Library\StoreLendBookToPerson;
use App\Http\Requests\Library\StoreReturnBookFromPerson;

class LendingController extends Controller
{
    public function index(Request $request) {
        // TODO authentication
        return view('library.lending.index', [ ]);
    }

    public function persons() {
        // TODO authentication

        return view('library.lending.persons', [ 
            'persons' => Person::has('bookLendings')->get()->sortBy('fullName'),
        ]);
    }

    public function person(Person $person) {
        // TODO authentication

        return view('library.lending.person', [ 
            'person' => $person,
            'lendings' => $person->bookLendings()->whereNull('returned_date')->orderBy('return_date', 'asc')->get(),
        ]);
    }

    public function personLog(Person $person) {
        // TODO authentication

        return view('library.lending.personLog', [ 
            'person' => $person,
            'lendings' => $person->bookLendings()->orderBy('lending_date', 'desc')->paginate(25),
        ]);
    }

    public function books() {
        // TODO authentication

        return view('library.lending.books', [ 
            'books' => LibraryBook::has('lendings')->get()->sortBy('title'),
        ]);
    }

    public function book(LibraryBook $book) {
        // TODO authentication

        return view('library.lending.book', [ 
            'book' => $book,
        ]);
    }

    public function bookLog(LibraryBook $book) {
        // TODO authentication

        return view('library.lending.bookLog', [ 
            'book' => $book,
            'lendings' => $book->lendings()->orderBy('lending_date', 'desc')->paginate(25),
        ]);
    }

    public function lendBookToPerson(Person $person, StoreLendBookToPerson $request) {
        // TODO authentication

        $book = LibraryBook::findOrFail($request->book_id);
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $duration = \Setting::get('library.default_lening_duration_days', LibrarySettingsController::DEFAULT_LENING_DURATION_DAYS);
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return redirect()->route('library.lending.person', $person)
            ->with('success', __('library.book_lent'));	
    }

    public function lendBook(LibraryBook $book, StoreLendBook $request) {
        // TODO authentication
        // TODO validate no date conflict

        $person = Person::findOrFail($request->person_id);
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $duration = \Setting::get('library.default_lening_duration_days', LibrarySettingsController::DEFAULT_LENING_DURATION_DAYS);
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return redirect()->route('library.lending.book', $book)
            ->with('success', __('library.book_lent'));	
    }

    public function returnBookFromPerson(Person $person, StoreReturnBookFromPerson $request) {
        // TODO authentication

        $lending = LibraryLending::where('book_id', $request->book_id)
            ->where('person_id', $person->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $lending->returned_date = Carbon::today();
        $lending->save();

        return redirect()->route('library.lending.person', $person)
            ->with('success', __('library.book_returned'));	
    }

    public function returnBook(LibraryBook $book) {
        // TODO authentication

        $lending = LibraryLending::where('book_id', $book->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $lending->returned_date = Carbon::today();
        $lending->save();

        return redirect()->route('library.lending.book', $book)
            ->with('success', __('library.book_returned'));	
    }
    
}
