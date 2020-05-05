<?php

namespace App\Http\Controllers\Library\API;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\People\Person;

class LendingController extends Controller
{
    public function stats()
    {
        return [
            'num_borrowers' => Person::query()
                ->whereHas('bookLendings', function ($query) {
                    $query->whereNull('returned_date');
                })
                ->count(),
            'num_lent_books' => LibraryBook::query()
                ->whereHas('lendings', function ($query) {
                    $query->whereNull('returned_date');
                })
                ->count(),
        ];
    }
}
