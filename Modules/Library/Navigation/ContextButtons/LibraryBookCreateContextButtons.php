<?php

namespace Modules\Library\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Library\Entities\LibraryBook;

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
