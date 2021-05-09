<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && !in_array($ability, ['update', 'delete', 'undoBooking'])) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    public function create(User $user)
    {
        return $user->hasPermission('accounting.transactions.create');
    }

    public function update(User $user, Transaction $transaction)
    {
        if (!$transaction->booked && $transaction->controlled_at === null) {
            return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.update');
        }
        return false;
    }

    public function delete(User $user, Transaction $transaction)
    {
        if (!$transaction->booked && $transaction->controlled_at === null) {
            return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.delete');
        }
        return false;
    }

    public function undoBooking(User $user, Transaction $transaction)
    {
        if ($transaction->booked) {
            return $user->isSuperAdmin() || $user->can('book-accounting-transactions-externally');
        }
        return false;
    }

    public function undoControlling(User $user, Transaction $transaction)
    {
        if ($transaction->booked) {
            return $user->isSuperAdmin() || $user->id === $transaction->controlled_by;
        }
        return false;
    }
}
