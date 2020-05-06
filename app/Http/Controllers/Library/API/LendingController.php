<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreExtendBookToPerson;
use App\Http\Requests\Library\StoreLendBookToPerson;
use App\Http\Requests\Library\StoreReturnBookFromPerson;
use App\Models\Library\LibraryBook;
use App\Http\Resources\Library\LibraryLending as LibraryLendingResource;
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

    public function person(Person $person, Request $request)
    {
        $this->authorize('list', Person::class);

        $lendings = $person->bookLendings()
            ->whereNull('returned_date')
            ->orderBy('return_date', 'asc')
            ->get();
        $can_lend = ! Setting::has('library.max_books_per_person') || Setting::get('library.max_books_per_person') > $lendings->count();
        $default_extend_duration = Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE);

        return LibraryLendingResource::collection($lendings)
            ->additional(['meta' => [
                'can_lend' => $can_lend,
                'can_register_book' => $request->user()->can('create', LibraryBook::class),
                'default_extend_duration' => intval($default_extend_duration),
            ]]);
    }

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

    public function extendBookToPerson(Person $person, StoreExtendBookToPerson $request)
    {
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

    public function returnBookFromPerson(Person $person, StoreReturnBookFromPerson $request)
    {
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
}
