<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    /**
     * ユーザー全体の閲覧は管理者のみ
     */
    public function viewAny(User $user): ?bool
    {
        // if ($user->can('users.view')) {
        //     return true;
        // }
        // return false;
        return $user->hasRole('manager');
    }

    /**
     * 自身のユーザー情報の閲覧はゲスト以外全員可
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('manager')) {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * 本人に自分のユーザー情報の更新を許可する
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * 本人には自分のユーザー情報の削除は許可しない
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
