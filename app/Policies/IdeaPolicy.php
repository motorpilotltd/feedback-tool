<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use App\Traits\WithCustomPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization, WithCustomPolicy;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Idea $idea): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Idea $idea): bool
    {
        return $user->id === (int) $idea->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Idea $idea): bool
    {
        if ($this->isSandboxMode($idea->product)) {
            return true;
        }

        $user->load('permissions');

        return $user->id === (int) $idea->author_id
            || $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$idea->productId)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Idea $idea): bool
    {
        //
    }

    /**
     * Determine whether the user can manage the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manage(User $user, Idea $idea)
    {
        if ($this->isSandboxMode($idea->product)) {
            return true;
        }

        $user->load('permissions');

        return $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$idea->productId)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Idea $idea): bool
    {
        //
    }

    public function pinIdeaComment(User $user, Idea $idea)
    {
        if ($this->update($user, $idea) || $this->manage($user, $idea)) {
            return true;
        }

        return false;
    }
}
