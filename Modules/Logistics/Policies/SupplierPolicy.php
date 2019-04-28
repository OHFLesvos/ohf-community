<?php

namespace Modules\Logistics\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use Modules\Logistics\Entities\Supplier;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list suppliers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('logistics.suppliers.view');
    }

    /**
     * Determine whether the user can view the supplier.
     *
     * @param  User  $user
     * @param  Supplier  $supplier
     * @return mixed
     */
    public function view(User $user, Supplier $supplier)
    {
        return $user->hasPermission('logistics.suppliers.view');
    }

    /**
     * Determine whether the user can create suppliers.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('logistics.suppliers.manage');
    }

    /**
     * Determine whether the user can update the supplier.
     *
     * @param  User  $user
     * @param  Supplier  $supplier
     * @return mixed
     */
    public function update(User $user, Supplier $supplier)
    {
        return $user->hasPermission('logistics.suppliers.manage');
    }

    /**
     * Determine whether the user can delete the supplier.
     *
     * @param  User  $user
     * @param  Supplier  $supplier
     * @return mixed
     */
    public function delete(User $user, Supplier $supplier)
    {
        return $user->hasPermission('logistics.suppliers.manage');
    }

}
