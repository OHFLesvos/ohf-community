<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.view')
            || $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        return $user->hasPermission('fundraising.donors_donations.view')
            || $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.view')
            || $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $comment->user_id === $user->id
            && (
                $user->hasPermission('fundraising.donors_donations.view')
                || $user->hasPermission('cmtyvol.manage')
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->user_id === $user->id
            && (
                $user->hasPermission('fundraising.donors_donations.view')
                || $user->hasPermission('cmtyvol.manage')
            );
    }
}
