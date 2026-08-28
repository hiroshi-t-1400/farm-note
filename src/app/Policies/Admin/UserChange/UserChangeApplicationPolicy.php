<?php

namespace App\Policies\Admin\UserChange;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserChangeApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('user-change.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserChangeApplication $userChangeRequest): bool
    {
        return $user->hasPermissionTo('user-change.viewAny');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('user-change.request');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserChangeApplication $userChangeRequest): bool
    {
        return $user->id === $userChangeRequest->requester->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserChangeApplication $userChangeRequest): bool
    {
        return $user->id === $userChangeRequest->requester->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserChangeApplication $userChangeRequest): bool
    {
        return $user->hasPermissionTo('user-change.request');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserChangeApplication $userChangeRequest): bool
    {
        return false;
    }
}
