<?php

namespace App\Http\Controllers\Library;

use App\Exports\Library\BorrowerExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Requests\Library\StoreExtendBook;
use App\Http\Requests\Library\StoreExtendBookToPerson;
use App\Http\Requests\Library\StoreLendBook;
use App\Http\Requests\Library\StoreLendBookToPerson;
use App\Http\Requests\Library\StoreReturnBookFromPerson;
use App\Http\Requests\People\StorePerson;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use App\Settings\Library\DefaultLendingDurationDays;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Setting;

class LendingController extends Controller
{
    use ExportableActions;

    public function index()
    {
        return view('library.lending.index', [
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
        ]);
    }

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

    public function storePerson(StorePerson $request)
    {
        $this->authorize('create', Person::class);

        $person = new Person();
        $person->name = $request->name;
        $person->family_name = $request->family_name;
        $person->gender = $request->gender;
        $person->date_of_birth = $request->date_of_birth;
        $person->police_no = ! empty($request->police_no) ? $request->police_no : null;
        $person->nationality = ! empty($request->nationality) ? $request->nationality : null;
        $person->save();

        return redirect()
            ->route('library.lending.person', $person)
            ->with('success', __('people.person_registered'));
    }

    public function person(Person $person)
    {
        $this->authorize('list', Person::class);

        return view('library.lending.person', [
            'person' => $person,
            'lendings' => $person->bookLendings()->whereNull('returned_date')->orderBy('return_date', 'asc')->get(),
            'default_extend_duration' => Setting::get('library.default_lending_duration_days', DefaultLendingDurationDays::DEFAULT_VALUE),
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

    public function lendBookToPerson(Person $person, StoreLendBookToPerson $request)
    {
        $this->authorize('create', LibraryLending::class);

        if (! empty($request->title)) {
            $book = new LibraryBook();
            $book->title = $request->title;
            $book->author = $request->author;
            $book->isbn = $request->isbn;
            $book->language = $request->language;
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

        return redirect()
            ->route('library.lending.person', $person)
            ->with('success', __('library.book_lent'));
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

    public function extendBookToPerson(Person $person, StoreExtendBookToPerson $request)
    {
        $lending = LibraryLending::where('book_id', $request->book_id)
            ->where('person_id', $person->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $this->authorize('update', $lending);
        $lending->return_date = $lending->return_date->addDays($request->days);
        $lending->save();

        return redirect()
            ->route('library.lending.person', $person)
            ->with('success', __('library.book_extended'));
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

    public function returnBookFromPerson(Person $person, StoreReturnBookFromPerson $request)
    {
        $lending = LibraryLending::where('book_id', $request->book_id)
            ->where('person_id', $person->id)
            ->whereNull('returned_date')
            ->firstOrFail();
        $this->authorize('update', $lending);
        $lending->returned_date = Carbon::today();
        $lending->save();

        return redirect()
            ->route('library.lending.person', $person)
            ->with('success', __('library.book_returned'));
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

    protected function exportAuthorize()
    {
        $this->authorize('list', Person::class);
    }

    protected function exportView(): string
    {
        return 'library.lending.export';
    }

    protected function exportViewArgs(): array
    {
        return [
            'selections' => [
                'all' => __('app.all'),
                'active' => __('app.active')
            ],
            'selection' => 'active',
        ];
    }

    protected function exportValidateArgs(): array
    {
        return [
            'selection' => [
                'required',
                Rule::in(['all', 'active']),
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        return __('library.borrowers') . '_' . Carbon::now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $export = new BorrowerExport();
        $export->activeOnly = $request->selection == 'active';
        return $export;
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        return $export->download($file_name . '.' . $file_ext);
    }
}
