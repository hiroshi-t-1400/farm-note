<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserApprovalRequest;
use App\Models\UserChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserApprovalController extends Controller
{
    // 一覧
    public function index()
    {
        $changeRequests = UserChangeRequest::where('status', 'pending')
            ->with(['targetUser', 'requester'])
            ->orderBy('created_at')
            ->cursorPaginate(15);

        return response()->view('/admin/approvals/index', compact('changeRequests'));
    }

    // 承認操作画面表示
    public function show(Request $request, UserChangeRequest $changeRequest)
    {
        return response()->view('admin.approvals.show', compact('changeRequest'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // 承認ロジック
    public function approve(Request $request, UserChangeRequest $changeRequest)
    {
        // policyで認可の設定
        // $this->authorize('approve', $request);

        try {
            // モデルにカプセル化されたビジネスロジックの実行
            $changeRequest->approve($request->user());

            session()->flash('success', '申請を承認しました。');

            // 成功ステータス（JSON）を返す
            return response()->json(['message' => 'success'], 200);

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
    public function reject(UserApprovalRequest $request, UserChangeRequest $changeRequest)
    {
        $validated = $request->validated();

        try {
            $changeRequest->reject($request->user(), $validated['rejection_reason'] ?? null);

            session()->flash('success', '申請を却下しました。');

            // 成功ステータス（JSON）を返す
            return response()->json(['message' => 'success'], 200);

        } catch (\LogicException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Throwable $e) {
            // その他のエラーをLogを保存、messegeとして読み出せるように
            Log::error('ユーザー登録棄却処理エラー', [
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
}
