<?php

namespace Modules\Library\Widgets;

use App\Widgets\Widget;

use App\Models\People\Person;
use Modules\Library\Entities\LibraryBook;

use Illuminate\Support\Facades\Auth;

class LibraryWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('operate-library');
    }

    function view(): string
    {
        return 'library::dashboard.widgets.library';
    }

    function args(): array {
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