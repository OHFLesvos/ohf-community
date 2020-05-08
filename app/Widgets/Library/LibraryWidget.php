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
            'num_borrowers' => Person::query()
                ->whereHas('bookLendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
            'num_lent_books' => LibraryBook::query()
                ->whereHas('lendings', fn ($query) => $query->whereNull('returned_date'))
                ->count(),
            'num_books' => LibraryBook::count(),
        ];
    }
}
