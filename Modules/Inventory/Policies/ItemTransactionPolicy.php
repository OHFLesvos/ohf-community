<?php

namespace Modules\Inventory\Policies;

use App\User;

use Modules\Inventory\Entities\InventoryItemTransaction;

use Illuminate\Auth\Access\HandlesAuthorization;

class ItemTransactionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('inventory.storage.view');
    }

    /**
     * Determine whether the user can view the inventory item transaction.
     *
     * @param  \App\User  $user
     * @param  \Modules\Inventory\Entities\InventoryItemTransaction  $inventoryItemTransaction
     * @return mixed
     */
    public function view(User $user, InventoryItemTransaction $inventoryItemTransaction)
    {
        return $user->hasPermission('inventory.storage.view');
    }

    /**
     * Determine whether the user can create inventory item transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('inventory.transactions.create');
    }

    /**
     * Determine whether the user can delete inventory item transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasPermission('inventory.transactions.delete');
    }
}
