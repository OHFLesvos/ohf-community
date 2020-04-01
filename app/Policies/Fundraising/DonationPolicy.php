<?php

namespace App\Policies\Fundraising;

use App\Models\Fundraising\Donation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list donors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('fundraising.donations.view');
    }

    /**
     * Determine whether the user can view the donation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return mixed
     */
    public function view(User $user, Donation $donation)
    {
        return $user->hasPermission('fundraising.donations.view');
    }

    /**
     * Determine whether the user can create donations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('fundraising.donations.register');
    }

    /**
     * Determine whether the user can update the donation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return mixed
     */
    public function update(User $user, Donation $donation)
    {
        return $user->hasPermission('fundraising.donations.edit');
    }

    /**
     * Determine whether the user can delete the donation.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return mixed
     */
    public function delete(User $user, Donation $donation)
    {
        return $user->hasPermission('fundraising.donations.edit');
    }
}
