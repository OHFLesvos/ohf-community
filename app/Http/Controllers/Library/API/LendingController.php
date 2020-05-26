<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreExtendBook;
use App\Http\Requests\Library\StoreExtendBookToPerson;
use App\Http\Requests\Library\StoreLendBook;
use App\Http\Requests\Library\StoreLendBookToPerson;
use App\Http\Requests\Library\StoreReturnBookFromPerson;
use App\Http\Resources\Library\Borrower;
use App\Http\Resources\Library\LentBook;
use App\Http\Resources\Library\LibraryLending as LibraryLendingResource;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use App\Settings\Library\DefaultLendingDurationDays;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $this->authorize('operate-library');

        return [
            'num_borrowers' => Person::query()
                ->whereHas('bookLendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
            'num_lent_books' => LibraryBook::query()
                ->whereHas('lendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
        ];
    }

    public function persons(Request $request)
    {
        $this->authorize('viewAny', Person::class);

        $pageSize = $request->input('pageSize', 25);

        $persons = Person::query()
            ->whereHas('bookLendings', fn ($query) => $query->active())
            ->with('bookLendings')
            ->get()
            ->sortBy('fullName')
            ->values()
            ->paginate($pageSize);

        return Borrower::collection($persons);
    }

    public function books(Request $request)
    {
        $this->authorize('viewAny', LibraryBook::class);

        $pageSize = $request->input('pageSize', 25);

        $books = LibraryBook::query()
            ->whereHas('lendings', fn ($query) => $query->active())
            ->with('lendings')
            ->orderBy('title')
            ->get()
            ->paginate($pageSize);

        return LentBook::collection($books);
    }

    /**
     * Gets the book lendings of a person
     *
     * @param Person $person
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function person(Person $person, Request $request)
    {
        $this->authorize('view', $person);
        $this->authorize('viewAny', LibraryBook::class);

        $lendings = $person->bookLendings()
            ->with('book')
            ->whereNull('returned_date')
            ->orderBy('return_date', 'asc')
            ->get();
        $can_lend = ! Setting::has('library.max_books_per_person') || Setting::get('library.max_books_per_person') > $lendings->count();
        $default_extend_duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);

        return LibraryLendingResource::collection($lendings)
            ->additional([
                'meta' => [
                    'can_lend' => $can_lend,
                    'can_register_book' => $request->user()->can('create', LibraryBook::class),
                    'default_extend_duration' => intval($default_extend_duration),
                ],
            ]);
    }

    /**
     * Lends a book to a person
     *
     * @param Person $person
     * @param StoreLendBookToPerson $request
     * @return \Illuminate\Http\Response
     */
    public function lendBookToPerson(Person $person, StoreLendBookToPerson $request)
    {
        $this->authorize('create', LibraryLending::class);

        if (! empty($request->title)) {
            $book = new LibraryBook();
            $book->title = $request->title;
            $book->author = $request->author;
            $book->isbn = $request->isbn;
            $book->language_code = $request->language_code;
            $book->save();
        } else {
            $book = LibraryBook::findOrFail($request->book_id);
        }
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return response()->json([
            'message' => __('library.book_lent'),
        ]);
    }

    /**
     * Extends a book lending of a person
     *
     * @param Person $person
     * @param StoreExtendBookToPerson $request
     * @return \Illuminate\Http\Response
     */
    public function extendBookToPerson(Person $person, StoreExtendBookToPerson $request)
    {
        $this->authorize('operate-library');

        $lending = LibraryLending::where('book_id', $request->book_id)
            ->where('person_id', $person->id)
            ->whereNull('returned_date')
            ->firstOrFail();

        $this->authorize('update', $lending);

        $lending->return_date = $lending->return_date->addDays($request->days);
        $lending->save();

        return response()->json([
            'message' => __('library.book_extended'),
        ]);
    }

    /**
     * Returns a book lent by a person
     *
     * @param Person $person
     * @param StoreReturnBookFromPerson $request
     * @return \Illuminate\Http\Response
     */
    public function returnBookFromPerson(Person $person, StoreReturnBookFromPerson $request)
    {
        $this->authorize('operate-library');

        $lending = LibraryLending::where('book_id', $request->book_id)
            ->where('person_id', $person->id)
            ->whereNull('returned_date')
            ->firstOrFail();

        $this->authorize('update', $lending);

        $lending->returned_date = Carbon::today();
        $lending->save();

        return response()->json([
            'message' => __('library.book_returned'),
        ]);
    }

    public function personLog(Person $person, Request $request)
    {
        $this->authorize('viewAny', Person::class);

        $pageSize = $request->input('pageSize', 25);

        $lendings = $person->bookLendings()
            ->with('book')
            ->orderBy('lending_date', 'desc')
            ->paginate($pageSize);

        return LibraryLendingResource::collection($lendings);
    }

    /**
     * Gets the lending of a book
     *
     * @param LibraryBook $book
     * @return void
     */
    public function book(LibraryBook $book)
    {
        $this->authorize('view', $book);
        $this->authorize('viewAny', LibraryBook::class);

        $lending = $book->lendings()
            ->with('person')
            ->whereNull('returned_date')
            ->first();

        if ($lending == null) {
            return response()->json(null);
        }

        $default_extend_duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);

        return (new LibraryLendingResource($lending))
            ->additional([
                'meta' => [
                    'default_extend_duration' => intval($default_extend_duration),
                ],
            ]);
    }

    public function lendBook(LibraryBook $book, StoreLendBook $request)
    {
        $this->authorize('create', LibraryLending::class);
        // TODO validate no date conflict

        $person = Person::where('public_id', $request->person_id)->firstOrFail();
        $lending = new LibraryLending();
        $lending->lending_date = Carbon::today();
        $duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);
        $lending->return_date = Carbon::today()->addDays($duration);
        $lending->person()->associate($person);
        $lending->book()->associate($book);
        $lending->save();

        return response()->json([
            'message' => __('library.book_lent'),
        ]);
    }

    public function extendBook(LibraryBook $book, StoreExtendBook $request)
    {
        $this->authorize('operate-library');

        $lending = LibraryLending::where('book_id', $book->id)
            ->whereNull('returned_date')
            ->firstOrFail();

        $this->authorize('update', $lending);

        $lending->return_date = $lending->return_date->addDays($request->days);
        $lending->save();

        return response()->json([
            'message' => __('library.book_extended'),
        ]);
    }

    public function returnBook(LibraryBook $book)
    {
        $this->authorize('operate-library');

        $lending = LibraryLending::where('book_id', $book->id)
            ->whereNull('returned_date')
            ->firstOrFail();

        $this->authorize('update', $lending);

        $lending->returned_date = Carbon::today();
        $lending->save();

        return response()->json([
            'message' => __('library.book_returned'),
        ]);
    }

    public function bookLog(LibraryBook $book, Request $request)
    {
        $this->authorize('view', $book);

        $pageSize = $request->input('pageSize', 25);

        $lendings = $book->lendings()
            ->with('person')
            ->orderBy('lending_date', 'desc')
            ->paginate($pageSize);

        return LibraryLendingResource::collection($lendings);
    }
}
