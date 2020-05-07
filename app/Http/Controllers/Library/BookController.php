<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\UpdateBook;
use App\Models\Library\LibraryBook;

class BookController extends Controller
{
    public function edit(LibraryBook $book)
    {
        $this->authorize('update', $book);

        return view('library.books.edit', [
            'book' => $book,
            'languages' => localized_language_names(),
        ]);
    }

    public function update(LibraryBook $book, UpdateBook $request)
    {
        $this->authorize('update', $book);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->language_code = $request->language_code;
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
