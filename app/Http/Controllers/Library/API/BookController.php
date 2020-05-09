<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\UpdateBook;
use App\Models\Library\LibraryBook;
use Illuminate\Http\Request;
use Scriptotek\GoogleBooks\GoogleBooks;
use App\Http\Resources\Library\LibraryBook as LibraryBookResource;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', LibraryBook::class);

        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'title',
                    'author',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'created_at');
        $sortDirection = $request->input('sortDirection', 'desc');
        $pageSize = $request->input('pageSize', 100);
        $filter = trim($request->input('filter', ''));

        $data = LibraryBook::query()
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize);

        return LibraryBookResource::collection($data);
    }

    public function store(UpdateBook $request)
    {
        $this->authorize('create', LibraryBook::class);

        $book = new LibraryBook();
        $book->fill($request->all());
        $book->save();

        return response()->json([
            'message' => __('library.book_registered'),
            'id' => $book->id,
        ]);
    }

    public function show(LibraryBook $book, Request $request)
    {
        $this->authorize('view', $book);

        return (new LibraryBookResource($book))
            ->additional(['meta' => [
                'can_delete' => $request->user()->can('delete', $book)
            ]]);
    }

    public function update(LibraryBook $book, UpdateBook $request)
    {
        $this->authorize('update', $book);

        $book->fill($request->all());
        $book->save();

        return response()->json([
            'message' => __('library.book_updated'),
        ]);
    }

    public function destroy(LibraryBook $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json([
            'message' => __('library.book_removed'),
        ]);
    }

    /**
     * Returns a list of filtered books for autocomplete input
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $this->authorize('viewAny', LibraryBook::class);

        $filterQuery = $request->query('query');
        $availableOnly = $request->query('available') != null;
        $records = LibraryBook::query()
            ->when($filterQuery, function ($query, $filterQuery) {
                return $query->forFilter($filterQuery);
            })
            ->when($availableOnly, function ($query, $filterQuery) {
                return $query->available();
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
