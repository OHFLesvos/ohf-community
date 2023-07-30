<?php

namespace App\Navigation\Drawer\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class CommunityVolunteersNavigationItem extends BaseNavigationItem
{
    protected string $route = 'cmtyvol.index';

    public function getCaption(): string
    {
        return __('Community Volunteers');
    }

    protected string $icon = 'id-badge';

    protected string|array $active = 'cmtyvol*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', CommunityVolunteer::class);
    }
}
