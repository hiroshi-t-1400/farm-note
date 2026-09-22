<?php

namespace App\Policies\Admin\UserChange;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class UserChangeApplicationPolicy
{
    const DENY_STATUS = ['rejected', 'approved', 'closed'];

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
    public function update(User $user, UserChangeApplication $userChangeRequest): Response
    {
        if (in_array($userChangeRequest->status, self::DENY_STATUS, true)) {
            return Response::deny('この申請は現在編集できません。');
        }

        if ($user->id !== $userChangeRequest->requester->id) {
            return Response::deny('この申請を編集する権限がありません。');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserChangeApplication $userChangeRequest): Response
    {
        if (in_array($userChangeRequest->status, self::DENY_STATUS, true)) {
            return Response::deny('この申請は現在削除できません。');
        }

        if ($user->id !== $userChangeRequest->requester->id) {
            return Response::deny('この申請を削除する権限がありません。');
        }

        return Response::allow();
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

    /**
     * 申請が却下されたことを確認したステータスに変更
     * 却下された申請は内容の修正を禁止されるが"確認"および"再申請"の操作履歴の記録は許可される
     */
    public function history(User $user, UserChangeApplication $userChangeRequest): Response
    {
        if ($user->id !== $userChangeRequest->requester->id) {
            return Response::deny('この申請を更新する権限がありません。');
        }

        return Response::allow();
    }
}
