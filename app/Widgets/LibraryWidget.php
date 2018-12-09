<?php

namespace App\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Person;
use App\LibraryBook;

class LibraryWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('operate-library');
    }

    function view(): string
    {
        return 'dashboard.widgets.library';
    }

    function args(): array {
        return [
            'num_borrowers' => Person::whereHas('bookLendings', function ($query) {
                    $query->whereNull('returned_date');
                })->count(),
            'num_lent_books' => LibraryBook::whereHas('lendings', function ($query) {
                    $query->whereNull('returned_date');
                })->count(),
        ];
    }
}