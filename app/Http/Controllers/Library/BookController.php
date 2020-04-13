<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\UpdateBook;
use App\Models\Library\LibraryBook;

class BookController extends Controller
{
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
}
