<?php

namespace App\Widgets\Library;

use App\Models\Library\LibraryBook;
use App\Models\People\Person;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class LibraryWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('operate-library');
    }

    public function view(): string
    {
        return 'library.dashboard.widgets.library';
    }

    public function args(): array
    {
        return [
            'num_borrowers' => Person::whereHas('bookLendings', function ($query) {
                $query->whereNull('returned_date');
            })->count(),
            'num_lent_books' => LibraryBook::whereHas('lendings', function ($query) {
                $query->whereNull('returned_date');
            })->count(),
            'num_books' => LibraryBook::count(),
        ];
    }
}
