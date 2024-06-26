<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Traits\WithCustomPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
    public function view(User $user, Comment $comment): bool
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
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === (int) $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($this->isSandboxMode($comment->idea->product)) {
            return true;
        }

        return $user->id === (int) $comment->user_id
            || $user->hasPermissionTo(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$comment->idea->productId)
            || $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Comment $comment): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        //
    }
}
