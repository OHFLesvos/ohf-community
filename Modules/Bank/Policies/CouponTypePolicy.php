<?php

namespace Modules\Bank\Policies;

use App\User;

use Modules\Bank\Entities\CouponType;

use Illuminate\Auth\Access\HandlesAuthorization;

class CouponTypePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list couponTypes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can view the couponType.
     *
     * @param  \App\User  $user
     * @param  \Modules\Bank\Entities\CouponType  $couponType
     * @return mixed
     */
    public function view(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can create couponTypes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can update the couponType.
     *
     * @param  \App\User  $user
     * @param  \Modules\Bank\Entities\CouponType  $couponType
     * @return mixed
     */
    public function update(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can delete the couponType.
     *
     * @param  \App\User  $user
     * @param  \Modules\Bank\Entities\CouponType  $couponType
     * @return mixed
     */
    public function delete(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.configure');
    }
}
