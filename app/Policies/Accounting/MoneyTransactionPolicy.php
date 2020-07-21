<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MoneyTransactionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && !in_array($ability, ['update', 'delete', 'undoBooking'])) {
            return true;
        }
    }

    /**
     * Determine whether the user can list donors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    /**
     * Determine whether the user can view the money transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\MoneyTransaction  $moneyTransaction
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
     * @param  \App\Models\Accounting\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function update(User $user, MoneyTransaction $moneyTransaction)
    {
        if (!$moneyTransaction->booked && $moneyTransaction->controlled_at === null) {
            return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.update');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the money transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function delete(User $user, MoneyTransaction $moneyTransaction)
    {
        if (!$moneyTransaction->booked && $moneyTransaction->controlled_at === null) {
            return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.delete');
        }
        return false;
    }

    /**
     * Determine whether the user can mark a booked transaction as unbooked again.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function undoBooking(User $user, MoneyTransaction $moneyTransaction)
    {
        if ($moneyTransaction->booked) {
            return $user->isSuperAdmin() || $user->can('book-accounting-transactions-externally');
        }
        return false;
    }

    /**
     * Determine whether the user can mark a controlled transaction as uncontrolled again.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Accounting\MoneyTransaction  $moneyTransaction
     * @return mixed
     */
    public function undoControlling(User $user, MoneyTransaction $moneyTransaction)
    {
        if ($moneyTransaction->booked) {
            return $user->isSuperAdmin() || $user->id === $moneyTransaction->controlled_by;
        }
        return false;
    }
}
