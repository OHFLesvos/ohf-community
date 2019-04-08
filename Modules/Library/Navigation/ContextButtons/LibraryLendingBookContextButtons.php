<?php

namespace Modules\Library\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LibraryLendingBookContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $book = $view->getData()['book'];
        return [
            'log' => [
                'url' => route('library.lending.bookLog', $book),
                'caption' => __('app.log'),
                'icon' => 'list',
                'authorized' => $book->lendings()->count() > 0 && Auth::user()->can('view', $book),
            ],
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
