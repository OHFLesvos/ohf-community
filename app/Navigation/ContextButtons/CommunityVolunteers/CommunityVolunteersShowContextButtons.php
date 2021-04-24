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
            'action' => [
                'url' => route('cmtyvol.edit', $cmtyvol),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $cmtyvol),
            ],
            'vcard' => [
                'url' => route('cmtyvol.vcard', $cmtyvol),
                'caption' => __('app.vcard'),
                'icon' => 'address-card',
                'authorized' => Auth::user()->can('view', $cmtyvol),
            ],
            'delete' => [
                'url' => route('cmtyvol.destroy', $cmtyvol),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $cmtyvol),
                'confirmation' => __('app.really_delete_community_volunteer'),
            ],
            'back' => [
                'url' => route('cmtyvol.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', CommunityVolunteer::class),
            ],
        ];
    }
}
