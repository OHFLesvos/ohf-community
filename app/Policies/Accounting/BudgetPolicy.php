<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Budget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetPolicy
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.budgets.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accounting\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Budget $budget)
    {
        return $user->hasPermission('accounting.budgets.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('accounting.budgets.manage');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accounting\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Budget $budget)
    {
        return $user->hasPermission('accounting.budgets.manage');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accounting\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Budget $budget)
    {
        return $user->hasPermission('accounting.budgets.manage');
    }
}
