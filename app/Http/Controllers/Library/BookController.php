<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LibraryBook;
use Scriptotek\GoogleBooks\GoogleBooks;
use App\Http\Requests\Library\UpdateBook;

class BookController extends Controller
{
    public function index() {
        $this->authorize('list', LibraryBook::class);

        return view('library.books.index', [ 
            'books' => LibraryBook::orderBy('title')->paginate(100),
            'num_books' => LibraryBook::count(),
        ]);
    }

    public function create() {
        $this->authorize('create', LibraryBook::class);

        return view('library.books.create', [ 
        ]);
    }
    
    public function store(UpdateBook $request) {
        $this->authorize('create', LibraryBook::class);

        $book = new LibraryBook();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->language = $request->language;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect()->route('library.books.index', $book)
            ->with('success', __('library.book_registered'));	
    }
    
    public function edit(LibraryBook $book) {
        $this->authorize('update', $book);

        return view('library.books.edit', [ 
            'book' => $book,
        ]);
    }
    
    public function update(LibraryBook $book, UpdateBook $request) {
        $this->authorize('update', $book);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->language = $request->language;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect()->route('library.lending.book', $book)
            ->with('success', __('library.book_updated'));	
    }

    public function destroy(LibraryBook $book) {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()->route('library.books.index')
            ->with('success', __('library.book_removed'));	
    }

	public function filter(Request $request) {
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
            ->map(function($e){ 
                $val = $e->title;
                if (!empty($e->author)) {
                    $val.= ' (' . $e->author . ')';
                }
                if (!empty($e->isbn)) {
                    $val.= ', ' . $e->isbn;
                }
                return [
                    'value' => $val,
                    'data' => $e->id,
                ]; 
            });
        return response()->json(["suggestions" => $records]);
    }

    public function findIsbn($isbn) {
        $books = new GoogleBooks([
            'key' => \Setting::get('google.api_key'),
        ]);
        $volume = $books->volumes->byIsbn($isbn);
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
