<?php

namespace App\Policies\Fundraising;

use App\Models\Fundraising\Donor;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonorPolicy
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
        return $user->hasPermission('fundraising.donors.view');
    }

    /**
     * Determine whether the user can view the donor.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return mixed
     */
    public function view(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors.view');
    }

    /**
     * Determine whether the user can create donors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('fundraising.donors.manage');
    }

    /**
     * Determine whether the user can update the donor.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return mixed
     */
    public function update(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors.manage');
    }

    /**
     * Determine whether the user can delete the donor.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return mixed
     */
    public function delete(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors.manage');
    }
}
