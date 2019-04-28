<?php

namespace Modules\Logistics\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use Modules\Logistics\Entities\Product;

class ProductPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list products.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('logistics.products.view');
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  User  $user
     * @param  Product  $product
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        return $user->hasPermission('logistics.products.view');
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('logistics.products.manage');
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  User  $user
     * @param  Product  $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        return $user->hasPermission('logistics.products.manage');
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  User  $user
     * @param  Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        return $user->hasPermission('logistics.products.manage');
    }
}
