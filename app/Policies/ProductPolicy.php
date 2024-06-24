<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\WithCustomPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization, WithCustomPolicy;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Product $product)
    {
        return $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can specify different author
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function specifyAuthor(User $user, Product $product)
    {
        if ($this->isSandboxMode($product)) {
            return true;
        }

        return $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Product $product)
    {
        //
    }
}
