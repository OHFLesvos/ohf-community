<?php

namespace App\Http\Controllers\Library;

use App\Exports\Library\BooksExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Http\Requests\Library\UpdateBook;
use App\Models\Library\LibraryBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    use ExportableActions;

    public function index()
    {
        $this->authorize('list', LibraryBook::class);

        return view('library.books.index', [
            'books' => LibraryBook::orderBy('title')->paginate(100),
            'num_books' => LibraryBook::count(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', LibraryBook::class);

        return view('library.books.create', [
        ]);
    }

    public function store(UpdateBook $request)
    {
        $this->authorize('create', LibraryBook::class);

        $book = new LibraryBook();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->language = $request->language;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect()
            ->route('library.books.index', $book)
            ->with('success', __('library.book_registered'));
    }

    public function edit(LibraryBook $book)
    {
        $this->authorize('update', $book);

        return view('library.books.edit', [
            'book' => $book,
        ]);
    }

    public function update(LibraryBook $book, UpdateBook $request)
    {
        $this->authorize('update', $book);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->language = $request->language;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect()
            ->route('library.lending.book', $book)
            ->with('success', __('library.book_updated'));
    }

    public function destroy(LibraryBook $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()
            ->route('library.books.index')
            ->with('success', __('library.book_removed'));
    }

    protected function exportAuthorize()
    {
        $this->authorize('list', LibraryBook::class);
    }

    protected function exportView(): string
    {
        return 'library.books.export';
    }

    protected function exportViewArgs(): array
    {
        return [
            'selections' => [
                'all' => __('library.all_books'),
                'lent' => __('library.lent_books')
            ],
            'selection' => 'all',
        ];
    }

    protected function exportValidateArgs(): array
    {
        return [
            'selection' => [
                'required',
                Rule::in(['all', 'lent']),
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        return __('library.books') . '_' . Carbon::now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $export = new BooksExport();
        $export->lentOnly = $request->selection == 'lent';
        return $export;
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        return $export->download($file_name . '.' . $file_ext);
    }

}
