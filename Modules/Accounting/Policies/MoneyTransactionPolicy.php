<?php

namespace Modules\Accounting\Policies;

use App\User;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Auth\Access\HandlesAuthorization;

class MoneyTransactionPolicy
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
        return $user->hasPermission('accounting.transactions.view');
    }

    /**
     * Determine whether the user can view the money transaction.
     *
     * @param  \App\User  $user
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function view(User $user, MoneyTransaction $moneyTransaction)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    /**
     * Determine whether the user can create money transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('accounting.transactions.create');
    }

    /**
     * Determine whether the user can update the money transaction.
     *
     * @param  \App\User  $user
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function update(User $user, MoneyTransaction $moneyTransaction)
    {
        return $user->hasPermission('accounting.transactions.update_delete');
    }

    /**
     * Determine whether the user can delete the money transaction.
     *
     * @param  \App\User  $user
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function delete(User $user, MoneyTransaction $moneyTransaction)
    {
        return $user->hasPermission('accounting.transactions.update_delete');
    }
}
