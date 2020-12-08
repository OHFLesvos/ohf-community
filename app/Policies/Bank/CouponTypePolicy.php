<?php

namespace App\Policies\Bank;

use App\Models\Bank\CouponType;
use App\Models\User;
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
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('bank.withdrawals.do');
    }

    /**
     * Determine whether the user can view the couponType.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank\CouponType  $couponType
     * @return mixed
     */
    public function view(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.withdrawals.do');
    }

    /**
     * Determine whether the user can create couponTypes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can update the couponType.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank\CouponType  $couponType
     * @return mixed
     */
    public function update(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.configure');
    }

    /**
     * Determine whether the user can delete the couponType.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank\CouponType  $couponType
     * @return mixed
     */
    public function delete(User $user, CouponType $couponType)
    {
        return $user->hasPermission('bank.configure');
    }
}
