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
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.view');
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @return mixed
     */
    public function view(User $user, Tag $tag)
    {
        return $user->hasPermission('fundraising.donors_donations.view');
    }

    /**
     * Determine whether the user can create tags.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }
}
