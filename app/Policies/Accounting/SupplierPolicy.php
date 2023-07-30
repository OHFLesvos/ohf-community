<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, Supplier $supplier)
    {
        return $user->hasPermission('accounting.transactions.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('accounting.suppliers.manage');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Supplier $supplier)
    {
        return $user->hasPermission('accounting.suppliers.manage');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Supplier $supplier)
    {
        return $user->hasPermission('accounting.suppliers.manage');
    }
}
