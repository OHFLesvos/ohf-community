<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LibraryBookEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $book = $view->getData()['book'];
        return [
            'delete' => [
                'url' => route('library.books.destroy', $book),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $book),
                'confirmation' => __('library::library.confirm_delete_book')
            ],
            'back' => [
                'url' => route('library.lending.book', $book),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $book),
            ]
        ];
    }

}
