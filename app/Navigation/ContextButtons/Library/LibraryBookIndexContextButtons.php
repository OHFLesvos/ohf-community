<?php

namespace App\Navigation\ContextButtons\Library;

use App\Models\Library\LibraryBook;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryBookIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('library.books.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', LibraryBook::class),
            ],
            'report' => [
                'url' => route('library.report'),
                'caption' => __('app.report'),
                'icon' => 'chart-pie',
                'authorized' => Auth::user()->can('viewAny', LibraryBook::class),
            ],
            'export' => [
                'url' => route('library.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Gate::allows('operate-library'),
            ],
        ];
    }

}
