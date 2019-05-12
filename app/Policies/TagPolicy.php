<?php

namespace App\Policies;

use App\User;
use App\Tag;

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
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('wiki.view')
            || $user->hasPermission('fundraising.donors.view');
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     * @return mixed
     */
    public function view(?User $user, Tag $tag)
    {
        if ($user === null && is_module_enabled('KB')) {
            return self::hasPublicArticles($tag);
        }        
        return $user->hasPermission('wiki.view')
            || $user->hasPermission('fundraising.donors.view')
            || self::hasPublicArticles($tag);
    }

    private static function hasPublicArticles(Tag $tag)
    {
        return $tag->wikiArticles()->where('public', true)->count() > 0;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors.manage');
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors.manage');
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return $user->hasPermission('wiki.edit')
            || $user->hasPermission('fundraising.donors.manage');
    }

}
