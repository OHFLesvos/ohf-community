<?php

namespace App\Policies\Fundraising;

use App\Models\Fundraising\Donor;
use App\Models\User;
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
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.view');
    }

    /**
     * Determine whether the user can view the donor.
     *
     * @return mixed
     */
    public function view(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors_donations.view');
    }

    /**
     * Determine whether the user can create donors.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can update the donor.
     *
     * @return mixed
     */
    public function update(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }

    /**
     * Determine whether the user can delete the donor.
     *
     * @return mixed
     */
    public function delete(User $user, Donor $donor)
    {
        return $user->hasPermission('fundraising.donors_donations.manage');
    }
}
