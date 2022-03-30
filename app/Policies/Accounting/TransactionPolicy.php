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
        if ($user->isSuperAdmin() && !in_array($ability, ['update', 'delete', 'control', 'undoControlling', 'undoBooking'])) {
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
        if ($transaction->booked || $transaction->controlled_at !== null) {
            return false;
        }

        return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.update');
    }

    public function updateReceipt(User $user, Transaction $transaction)
    {
        return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.update');
    }

    public function updateMetadata(User $user, Transaction $transaction)
    {
        return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.update_metadata');
    }

    public function delete(User $user, Transaction $transaction)
    {
        if ($transaction->booked || $transaction->controlled_at !== null) {
            return false;
        }

        return $user->isSuperAdmin() || $user->hasPermission('accounting.transactions.delete');
    }

    public function control(User $user, Transaction $transaction)
    {
        if (!$this->update($user, $transaction)) {
            return false;
        }

        $userHasRegisteredTransaction = $transaction->audits()
            ->where('user_id', $user->id)
            ->where('event', 'created')
            ->exists();

        return !$userHasRegisteredTransaction;
    }

    public function undoBooking(User $user, Transaction $transaction)
    {
        if (!$transaction->booked) {
            return false;
        }

        return $user->isSuperAdmin() || $user->can('book-accounting-transactions-externally');
    }

    public function undoControlling(User $user, Transaction $transaction)
    {
        if ($transaction->booked) {
            return false;
        }

        return $user->isSuperAdmin() || $user->id === $transaction->controlled_by;
    }
}
