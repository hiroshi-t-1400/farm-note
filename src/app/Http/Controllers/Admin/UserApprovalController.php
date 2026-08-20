<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserChangeRequest;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    // 一覧
    public function index()
    {
        $requested_users = UserChangeRequest::with(['targetUser', 'requester'])->get();

        return response()->view('/admin/approvals/index', compact('requested_users'));
    }

    // 承認操作画面表示
    public function show(Request $request, UserChangeRequest $changeRequest)
    {
        return response()->view('admin.approvals.users', compact('changeRequest'));
    }

    // 承認ロジック
    public function approve(Request $request, UserChangeRequest $changeRequest)
    {
        // policyで認可の設定
        // $this->authorize('approve', $request);

        $approver = $request->user();

        $changeRequest->approve($approver);

        return response()->json([
            'status' => 'success',
            'message' => '登録申請を承認し、ユーザー登録を完了しました。',
        ]);
    }

    // 棄却ロジック
    public function reject()
    {

    }
}
