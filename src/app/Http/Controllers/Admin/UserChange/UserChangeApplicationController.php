<?php
// /var/www/src/app/Http/Controllers/Admin/UserChange/UserChangeApplicationController.php
namespace App\Http\Controllers\Admin\UserChange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserChange\CreateRequest;
use App\Http\Requests\Admin\UserChange\UpdateRequest;
use App\Http\Requests\Admin\UserChange\UpdateSubmitRequest;
use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

class UserChangeApplicationController extends Controller
{
    // 一覧
    public function index(): Response
    {
        $changeRequests = UserChangeApplication::query()
            ->defaultSort()
            ->paginate(15);  // モデルにカプセル化したScopeを呼び出す

        return response()->view('admin.requests.users.index', compact('changeRequests'));
    }

    // show と editを兼用
    public function edit(Request $request, UserChangeApplication $changeRequest): Response
    {
        Gate::authorize('view', $changeRequest);

        $changeRequest->load(['targetUser', 'requester']);

        if($changeRequest->status === 'rejected') {
            return response()->view('admin.requests.users.reapply', compact('changeRequest'));
        } else {
            return response()->view('admin.requests.users.edit', compact('changeRequest'));
        }
    }

    // 申請作成画面
    /**
     * @param string $actionType [create, update]
     */
    public function create(string $actionType, ?User $targetUser = null): Response|RedirectResponse
    {
        $requestData = [];

        if ($targetUser !== null) {
            $targetUser->load('roles');
            $requestData['targetUser'] = $targetUser;
        }

        $requestData['actionType'] = $actionType;

        return response()->view('admin.requests.users.create', compact('requestData'));
    }

    // 新規登録post
    public function storeCreate(CreateRequest $requestData): JsonResponse
    {
        $actionType = 'create';

        try {
            $validated = $requestData->validated();

            UserChangeApplication::create([
                'action_type' => $actionType,
                'target_user_id' => null,
                'payload' => $validated,
                'status' => UserChangeApplication::STATUS_PENDING,
                'requested_by' => $requestData->user()->id,
            ]);
        } catch (\LogicException $e) {
            // 「既に処理済み」「ステータスが不整合」などの業務エラー ➔ 422
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // その他のエラーをLogを保存、messegeとして読み出せるように
            Log::error('申請処理エラー', [
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id ?? '',
                'user_id' => $requestData->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'サーバーエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'ユーザー登録の申請を送信しました。',
        ]);
    }

    // ユーザー情報の更新
    public function storeUpdate(UpdateRequest $requestData, User $targetUser)
    {
        $actionType = 'update';
        try {
            $validated = $requestData->validated();

            UserChangeApplication::create([
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id,
                'payload' => $validated,
                'status' => UserChangeApplication::STATUS_PENDING,
                'requested_by' => $requestData->user()->id,
            ]);

        } catch (\LogicException $e) {
            // 「既に処理済み」「ステータスが不整合」などの業務エラー ➔ 422
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // その他のエラーをLogを保存、messegeとして読み出せるように
            Log::error('申請処理エラー', [
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id ?? '',
                'user_id' => $requestData->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'サーバーエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'ユーザー情報更新の申請を送信しました。',
        ]);
    }

    // ユーザー削除の申請
    public function storeDisable(Request $request, string $actionType, User $targetUser)
    {
        UserChangeApplication::create([
            'action_type' => $actionType,
            'target_user_id' => $targetUser->id,
            'status' => UserChangeApplication::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'ユーザー削除の申請を送信しました。'
        ]);
    }

    /**
     * 申請内容の更新
     *
     * @param User|null $targetUser FormRequestにてID等の重複のバリデーションを行うためにルートモデルバインディングしている
     */
    public function update(
        UpdateSubmitRequest $request,
        UserChangeApplication $changeRequest,
        ?User $targetUser = null
    ): JsonResponse {

        Gate::authorize('update', $changeRequest);

        try {
            $validated = $request->validated();

            if (!$request->filled('password')) {
                unset($validated['password']);
            }

            $changeRequest->update([
                'payload' => $validated,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'サーバーエラーが発生しました。',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => '申請内容を更新しました。',
        ]);
    }

    // 申請の撤回・削除
    public function destroy(Request $request, UserChangeApplication $changeRequest)
    {
        Gate::authorize('delete', $changeRequest);

        $changeRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => '申請を削除しました。',
        ]);
    }

    // 却下状態を確認した記録
    public function acknowledge(UserChangeApplication $changeRequest)
    {
        Gate::authorize('history', $changeRequest);

        $changeRequest->update([
            'rejection_acknowledge_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '却下された申請を確認済みに更新しました。',
        ]);
    }

    // 再申請した記録
    public function reapply(UserChangeApplication $changeApplication)
    {
        Gate::authorize('history', $changeApplication);

        $changeApplication->update([
            'reapplied_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '再申請の履歴を記録しました。',
        ]);

    }
}
