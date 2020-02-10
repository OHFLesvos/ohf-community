<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Library\LibraryBook;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LibraryBookCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('library.books.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', LibraryBook::class),
            ]
        ];
    }

}
