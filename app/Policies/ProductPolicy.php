<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\WithCustomPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization, WithCustomPolicy;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, Product $product): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can specify different author
     *
     * @return Response|bool
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
     * @return Response|bool
     */
    public function delete(User $user, Product $product): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, Product $product): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Product $product): bool
    {
        //
    }
}
