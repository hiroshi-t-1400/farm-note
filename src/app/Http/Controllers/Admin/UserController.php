<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\UserChangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Manager roleが操作するユーザー登録用のコントローラー
    //

    // ユーザーの閲覧
    public function index()
    {

    }

    //
    public function show()
    {
        return response()->view(/* 登録画面 */);
    }

    public function create()
    {
        return response()->view('/admin.users.create');
    }

    public function store(RegisterRequest $request): JsonResponse
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

}
