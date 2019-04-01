<?php

namespace Modules\Inventory\Policies;

use App\User;

use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\Auth\Access\HandlesAuthorization;

class StoragePolicy
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
        return $user->hasPermission('inventory.storage.view');
    }

    /**
     * Determine whether the user can view the inventory storage.
     *
     * @param  \App\User  $user
     * @param  \Modules\Inventory\Entities\InventoryStorage  $inventoryStorage
     * @return mixed
     */
    public function view(User $user, InventoryStorage $inventoryStorage)
    {
        return $user->hasPermission('inventory.storage.view');
    }

    /**
     * Determine whether the user can create inventory storages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('inventory.storage.manage');
    }

    /**
     * Determine whether the user can update the inventory storage.
     *
     * @param  \App\User  $user
     * @param  \Modules\Inventory\Entities\InventoryStorage  $inventoryStorage
     * @return mixed
     */
    public function update(User $user, InventoryStorage $inventoryStorage)
    {
        return $user->hasPermission('inventory.storage.manage');
    }

    /**
     * Determine whether the user can delete the inventory storage.
     *
     * @param  \App\User  $user
     * @param  \Modules\Inventory\Entities\InventoryStorage  $inventoryStorage
     * @return mixed
     */
    public function delete(User $user, InventoryStorage $inventoryStorage)
    {
        return $user->hasPermission('inventory.storage.manage');
    }
}
