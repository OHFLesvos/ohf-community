<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use Illuminate\Http\Request;
use Scriptotek\GoogleBooks\GoogleBooks;

class BookController extends Controller
{
    /**
     * Returns a list of filtered books for autocomplete input
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $this->authorize('list', LibraryBook::class);

        $filterQuery = $request->query('query');
        $records = LibraryBook::query()
            ->when($filterQuery, function ($query, $filterQuery) {
                return $query->forFilter($filterQuery);
            })
            ->orderBy('title')
            ->orderBy('author')
            ->limit(10)
            ->get()
            ->map(fn (LibraryBook $book) => [
                'value' => $book->label,
                'data' => $book->id,
            ]);

        return response()->json([
            'suggestions' => $records,
        ]);
    }

    /**
     * Finds information about a book by ISBN from Google books API
     *
     * @param GoogleBooks $books
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findIsbn(GoogleBooks $books, Request $request)
    {
        $request->validate([
            'isbn' => [
                'required',
                'isbn',
            ],
        ]);

        $volume = $books->volumes->byIsbn($request->isbn);
        if ($volume == null || $volume->volumeInfo == null) {
            return response()->json([], 404);
        }

        return response()->json([
            'title' => $volume->volumeInfo->title,
            'author' => implode(', ', $volume->volumeInfo->authors),
            'language' => $volume->volumeInfo->language,
        ]);
    }
}
