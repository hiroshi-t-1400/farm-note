<?php

namespace App\Policies\WorkLog;

use App\Models\User;
use App\Models\WorkLog\WorkLog;
use Illuminate\Auth\Access\Response;

class WorkLogPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasPermissionTo('work-logs.manage')){
            return true;
        }

        return null;
    }

    /**
     * 登録済みのユーザーは全て閲覧できる。ルートでログインだけ確認
     * 基本的に使用しない
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * 同上
     */
    public function view(User $user, WorkLog $workLog): bool
    {
        return false;
    }

    /**
     * 同上
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * 管理者:Ownerと作成者のみに許可
     *
     */
    public function update(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->created_by; // createdBy: int
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->created_by; // createdBy: int
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkLog $workLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkLog $workLog): bool
    {
        return false;
    }
}
