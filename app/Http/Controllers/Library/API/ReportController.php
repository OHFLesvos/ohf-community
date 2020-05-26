<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', LibraryBook::class);

        return response()->json([
            'borrwer_count' => Person::query()
                ->has('bookLendings')
                ->count(),
            'borrowers_currently_borrowed_count' => Person::query()
                ->whereHas('bookLendings', fn (Builder $query) => $query->active())
                ->count(),
            'borrowers_currently_overdue_count' => Person::query()
                ->whereHas('bookLendings', fn (Builder $query) => $query->overdue())
                ->count(),
            'borrwer_lendings_top' => Person::query()
                ->selectRaw('persons.*')
                ->selectRaw('(select count(*) from `library_lendings` where `persons`.`id` = `library_lendings`.`person_id`) as quantity')
                ->having('quantity', '>', 0)
                ->orderBy('quantity', 'desc')
                ->orderBy('name')
                ->limit('10')
                ->get()
                ->map(fn ($borrower) => array_merge($borrower->toArray(), [
                    'collapsed_name' => $request->user()->can('view', $borrower)
                        ? $borrower->fullName
                        : sprintf('%s. %s.', substr($borrower->name, 0, 1), substr($borrower->family_name, 0, 1)),
                    'age' => $borrower->age,
                ])),
            'borrwer_nationalities' => Person::query()
                ->select('nationality')
                ->selectRaw('COUNT(*) as quantity')
                ->has('bookLendings')
                ->groupBy('nationality')
                ->orderBy('quantity', 'desc')
                ->orderBy('nationality')
                ->get(),
            'borrwer_genders' => Person::query()
                ->select('gender')
                ->selectRaw('COUNT(*) as quantity')
                ->has('bookLendings')
                ->groupBy('gender')
                ->orderBy('quantity', 'desc')
                ->orderBy('gender')
                ->get(),

            'book_count' => LibraryBook::count(),
            'books_currently_borrowed_count' => LibraryBook::lent()->count(),
            'books_currently_overdue_count' => LibraryBook::query()
                ->whereHas('lendings', fn (Builder $query) => $query->overdue())
                ->count(),
            'book_lendings_unique_count' => LibraryBook::has('lendings')->count(),
            'book_lendings_all_count' => LibraryLending::count(),
            'book_lendings_top' => LibraryBook::query()
                ->select('title', 'author', 'language_code')
                ->selectRaw('(select count(*) from `library_lendings` where `library_books`.`id` = `library_lendings`.`book_id`) as quantity')
                ->having('quantity', '>', 0)
                ->orderBy('quantity', 'desc')
                ->orderBy('title')
                ->limit('10')
                ->get(),
            'book_languages' => LibraryBook::query()
                ->select('language_code')
                ->selectRaw('COUNT(*) as quantity')
                ->groupBy('language_code')
                ->orderBy('quantity', 'desc')
                ->get()
                ->map(fn ($e) => array_merge($e->toArray(), [
                    'language' => $e->language,
                ])),
        ]);
    }
}
