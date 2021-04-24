<?php

namespace App\Navigation\Drawer\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class CommunityVolunteersNavigationItem extends BaseNavigationItem
{
    protected $route = 'cmtyvol.index';

    public function getCaption(): string
    {
        return __('app.community_volunteers');
    }

    protected $icon = 'id-badge';

    protected $active = 'cmtyvol*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', CommunityVolunteer::class);
    }
}
