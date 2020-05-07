<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LibraryBookEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $book = $view->getData()['book'];
        return [
            'back' => [
                'url' => route('library.lending.book', $book),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $book),
            ],
        ];
    }

}
