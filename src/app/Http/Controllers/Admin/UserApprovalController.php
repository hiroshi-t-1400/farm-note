<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        try {
            // モデルにカプセル化されたビジネスロジックの実行
            $changeRequest->approve($request->user());

            return response()->json([
                'message' => '変更申請を承認し、ユーザー情報を反映しました。'
            ], 200);

        } catch (\LogicException $e) {
            // 「既に処理済み」「ステータスが不整合」などの業務エラー ➔ 422
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // その他のエラーをLogを保存、messegeとして読み出せるように
            Log::error('ユーザー承認処理エラー', [
                'change_request_id' => $changeRequest->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'システムエラーが発生しました。管理者にお問い合わせください。'
            ], 500);
        }
    }

    // 棄却ロジック
    public function reject()
    {

    }
}
