<?php

namespace Modules\Logistics\Policies;

use App\User;
use Modules\Logistics\Entities\Offer;

use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list offers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('logistics.products.view');
    }

    /**
     * Determine whether the user can view the offer.
     *
     * @param  User  $user
     * @param  Offer  $offer
     * @return mixed
     */
    public function view(User $user, Offer $offer)
    {
        return $user->hasPermission('logistics.products.view');
    }

    /**
     * Determine whether the user can create offers.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('logistics.products.manage');
    }

    /**
     * Determine whether the user can update the offer.
     *
     * @param  User  $user
     * @param  Offer  $offer
     * @return mixed
     */
    public function update(User $user, Offer $offer)
    {
        return $user->hasPermission('logistics.products.manage');
    }

    /**
     * Determine whether the user can delete the offer.
     *
     * @param  User  $user
     * @param  Offer  $offer
     * @return mixed
     */
    public function delete(User $user, Offer $offer)
    {
        return $user->hasPermission('logistics.products.manage');
    }
}
