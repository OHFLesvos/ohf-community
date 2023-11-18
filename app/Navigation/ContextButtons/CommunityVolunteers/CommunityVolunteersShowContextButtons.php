<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommunityVolunteersShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $cmtyvol = $view->getData()['cmtyvol'];

        return [
            'vcard' => [
                'url' => route('cmtyvol.vcard', $cmtyvol),
                'caption' => __('vCard'),
                'icon' => 'address-card',
                'authorized' => Auth::user()->can('view', $cmtyvol),
            ],
        ];
    }
}
