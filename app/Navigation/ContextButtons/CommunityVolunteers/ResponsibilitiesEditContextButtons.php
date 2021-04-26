<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\Responsibility;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsibilitiesEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $responsibility = $view->getData()['responsibility'];
        return [
            'delete' => [
                'url' => route('cmtyvol.responsibilities.destroy', $responsibility),
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $responsibility),
                'confirmation' => __('Really delete this responsibility?'),
            ],
            'back' => [
                'url' => route('cmtyvol.responsibilities.index'),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Responsibility::class),
            ],
        ];
    }
}
