<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Wallet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && ! in_array($ability, ['delete'])) {
            return true;
        }
    }

    public function list(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return mixed
     */
    public function view(User $user, Wallet $wallet)
    {
        return $user->hasPermission('accounting.configure');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return mixed
     */
    public function update(User $user, Wallet $wallet)
    {
        return $user->hasPermission('accounting.configure');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return mixed
     */
    public function delete(User $user, Wallet $wallet)
    {
        if ($user->isSuperAdmin() || $user->hasPermission('accounting.configure')) {
            return ! $wallet->is_default && Wallet::count() > 1;
        }
        return false;
    }
}
