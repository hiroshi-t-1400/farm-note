<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserChangeRequest;
use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserChangeRequestController extends Controller
{
    public function index(): Response
    {
        $changeRequests = UserChangeRequest::query()
            ->defaultSort()
            // ->get();
            ->paginate(15);  // モデルにカプセル化したScopeを呼び出す

        return response()->view('admin.users.index', compact('changeRequests'));
    }

    public function edit(Request $request, UserChangeRequest $changeRequest): Response
    {
        if ($request->user()->cannot('update', $changeRequest)) {
            abort(403);
        }

        $changeRequest->load(['targetUser', 'requester']);

        return response()->view('admin.users.edit', compact('changeRequest'));
    }

    // 申請作成画面
    /**
     * @param string $actionType [create, update, delete]
     */
    public function create(string $actionType, ?User $targetUser = null)
    {
        $requestData = [];

        if ($targetUser !== null) {
            $targetUser->load('roles');
            $requestData['targetUser'] = $targetUser;
        }

        $requestData['actionType'] = $actionType;

        return response()->view('admin.requests.users.create', compact('requestData'));
    }

    public function store(Request $request, StoreUserRequest $requestData, string $actionType, ?User $targetUser = null): JsonResponse
    {
        if ($actionType === 'delete') {
            UserChangeRequest::create([
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id,
                'status' => UserChangeRequest::STATUS_PENDING,
                'requested_by' => $request->user()->id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'ユーザー削除の申請を送信しました。'
            ]);
        }

        $validated = $requestData->validated();

        $validated['password'] = Hash::make($validated['password']);

        UserChangeRequest::create([
            'action_type' => $actionType,
            'target_user_id' => $targetUser?->id ?? null,
            'payload' => $validated,
            'status' => UserChangeRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ]);

        $message = $actionType === 'create'
            ? 'ユーザー登録の申請を送信しました。'
            : 'ユーザー情報更新の申請を送信しました。';

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    public function update(UpdateUserChangeRequest $request, UserChangeRequest $changeRequest): JsonResponse
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            // payload配列に生データとして置かれるのでハッシュ化
            $validated['password'] = Hash::make($request->password);
        } else {
            // 空欄の場合は更新対象から除外
            unset($validated['password']);
        }

        $changeRequest->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => '申請内容を更新しました。',
        ]);
    }
}
