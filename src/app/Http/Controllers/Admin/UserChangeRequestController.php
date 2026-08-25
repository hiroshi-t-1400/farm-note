<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserChangeRequest;
use App\Models\UserChangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserChangeRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-change.request', only: ['create', 'store']),
            new Middleware('permission:user-change.viewAny', only: ['index']),
        ];
    }


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

    public function create()
    {
        return response()->view('admin.users.create');
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        UserChangeRequest::create([
            'action_type' => 'create',
            'target_user_id' => null,
            'payload' => $validated,
            'status' => UserChangeRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'pending',
            'message' => 'ユーザー登録の申請を送信しました。'
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
