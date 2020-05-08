<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryLendingBookContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $book = $view->getData()['book'];
        return [
            'edit' => [
                'url' => route('library.books.edit', $book),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'authorized' => Auth::user()->can('update', $book),
            ],
            'back' => [
                'url' => route('library.lending.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('operate-library'),
            ],
        ];
    }

}
