<?php

namespace App\View\Widgets;

use App\Models\Library\LibraryBook;
use App\Models\People\Person;
use Illuminate\Support\Facades\Auth;

class LibraryWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('operate-library');
    }

    public function render()
    {
        return view('widgets.library', [
            'num_borrowers' => Person::query()
                ->whereHas('bookLendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
            'num_lent_books' => LibraryBook::query()
                ->whereHas('lendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
            'num_books' => LibraryBook::count(),
        ]);
    }
}
