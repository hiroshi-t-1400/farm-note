<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Auth\Access\Response;

class UserChangeRequestPolicy
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
    public function view(User $user, UserChangeRequest $userChangeRequest): bool
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
    public function update(User $user, UserChangeRequest $userChangeRequest): bool
    {
        return $user->id === $userChangeRequest->requester->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserChangeRequest $userChangeRequest): bool
    {
        return $user->id === $userChangeRequest->requester->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserChangeRequest $userChangeRequest): bool
    {
        return $user->hasPermissionTo('user-change.request');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserChangeRequest $userChangeRequest): bool
    {
        return false;
    }
}
