<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LibraryLending;
use App\LibraryBook;
use App\Person;
use Carbon\Carbon;
use App\Http\Requests\Library\StoreLendBook;
use App\Http\Requests\Library\StoreReturnBook;

class LendingController extends Controller
{
    public function index(Request $request) {
        // TODO authentication
        return view('library.lending.index', [ ]);
    }

    public function person(Person $person) {
        // TODO authentication
        return view('library.lending.person', [ 
            'person' => $person,
        ]);
    }

    public function book(LibraryBook $book) {
        // TODO authentication
        return view('library.lending.book', [ 
            'book' => $book,
        ]);
    }

    public function lendBook(Person $person, StoreLendBook $request) {
        // TODO authentication
        // TODO validate no date conflict

        $duration = \Setting::get('library.default_lening_duration_days', LibrarySettingsController::DEFAULT_LENING_DURATION_DAYS);

        $book = LibraryBook::findOrFail($request->book_id);
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return redirect()->route('library.lending.person', $person)
            ->with('success', __('library.book_lent'));	
    }

    public function returnBook(Person $person, StoreReturnBook $request) {
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
    
}
