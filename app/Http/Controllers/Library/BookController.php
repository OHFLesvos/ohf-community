<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;

class BookController extends Controller
{
    public function edit(LibraryBook $book)
    {
        $this->authorize('update', $book);

        return view('library.books.edit', [
            'book' => $book,
        ]);
    }
}
