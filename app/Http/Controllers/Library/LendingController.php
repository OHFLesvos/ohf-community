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
            'default_extend_duration' => Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE),
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

    public function lendBook(LibraryBook $book, StoreLendBook $request)
    {
        $this->authorize('create', LibraryLending::class);
        // TODO validate no date conflict

        $person = Person::findOrFail($request->person_id);
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return redirect()
            ->route('library.lending.book', $book)
            ->with('success', __('library.book_lent'));
    }

    public function extendBook(LibraryBook $book, StoreExtendBook $request)
    {
        $lending = LibraryLending::where('book_id', $book->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $this->authorize('update', $lending);
        $lending->return_date = $lending->return_date->addDays($request->days);
        $lending->save();

        return redirect()
            ->route('library.lending.book', $book)
            ->with('success', __('library.book_extended'));
    }

    public function returnBook(LibraryBook $book)
    {
        $lending = LibraryLending::where('book_id', $book->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $this->authorize('update', $lending);
        $lending->returned_date = Carbon::today();
        $lending->save();

        return redirect()
            ->route('library.lending.book', $book)
            ->with('success', __('library.book_returned'));
    }
}
