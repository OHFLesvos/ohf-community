<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommunityVolunteersEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $cmtyvol = $view->getData()['cmtyvol'];

        return [
            'back' => [
                'url' => route('cmtyvol.show', $cmtyvol),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $cmtyvol),
            ],
        ];
    }
}
