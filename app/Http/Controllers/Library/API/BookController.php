<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use Illuminate\Http\Request;
use Scriptotek\GoogleBooks\GoogleBooks;

class BookController extends Controller
{
    public function filter(Request $request)
    {
        $this->authorize('list', LibraryBook::class);

        $qry = LibraryBook::limit(10)
            ->orderBy('title')
            ->orderBy('author');
        if (isset($request->query()['query'])) {
            $qry->where('title', 'LIKE', '%' . $request->query()['query'] . '%')
                ->orWhere('author', 'LIKE', '%' . $request->query()['query'] . '%');
            if (preg_match('/^[0-9x-]+$/i', $request->query()['query'])) {
                $qry->orWhere('isbn10', 'LIKE', preg_replace('/[^+0-9x]/i', '', $request->query()['query']) . '%');
                $qry->orWhere('isbn13', 'LIKE', preg_replace('/[^+0-9x]/i', '', $request->query()['query']) . '%');
            }
        }
        $records = $qry->get()
            ->map(function ($e) {
                $val = $e->title;
                if (! empty($e->author)) {
                    $val .= ' (' . $e->author . ')';
                }
                if (! empty($e->isbn)) {
                    $val .= ', ' . $e->isbn;
                }
                return [
                    'value' => $val,
                    'data' => $e->id,
                ];
            });
        return response()->json(['suggestions' => $records]);
    }

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
