<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('wiki.view')
            || $user->hasPermission('fundraising.donors_donations.view');
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function view(?User $user, Tag $tag)
    {
        if ($user === null) {
            return self::hasPublicArticles($tag);
        }
        return $user->hasPermission('wiki.view')
            || $user->hasPermission('fundraising.donors_donations.view')
            || self::hasPublicArticles($tag);
    }

    private static function hasPublicArticles(Tag $tag)
    {
        return $tag->wikiArticles()->where('public', true)->count() > 0;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors_donations.manage');
    }

}
