<?php
// /var/www/src/app/Http/Controllers/Admin/UserChange/UserChangeApplicationController.php
namespace App\Http\Controllers\Admin\UserChange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserChange\StoreRequest;
use App\Http\Requests\Admin\UserChange\UpdateRequest;
use App\Http\Requests\Admin\UserChange\UpdateSubmitRequest;
use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
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

        return response()->view('admin.users.index', compact('changeRequests'));
    }

    public function edit(Request $request, UserChangeApplication $changeRequest): Response
    {
        Gate::authorize('update', $changeRequest);


        $changeRequest->load(['targetUser', 'requester']);

        return response()->view('admin.users.edit', compact('changeRequest'));
    }

    // 申請作成画面
    /**
     * @param string $actionType [create, update, disable]
     */
    public function create(string $actionType, ?User $targetUser = null): Response
    {
        $requestData = [];

        if ($targetUser !== null) {
            // 異常なアクセスへのフォールバック
            if (User::where('id', $targetUser->id)->exists()) {
                alert('送信データが異常です。');
                return response()->view('dashboard');
            }

            $targetUser->load('roles');
            $requestData['targetUser'] = $targetUser;
        }

        $requestData['actionType'] = $actionType;

        return response()->view('admin.requests.users.create', compact('requestData'));
    }

    // アクションに対応したstoreメソッド呼び出し
    public function store(
        Request $request,
        string $actionType,
        ?User $targetUser = null
    ): JsonResponse {

        $requestData = $request;

        try {
            $application = match ($request) {
                'create' => $this->storeCreate(
                    $requestData, $actionType
                ),

                'update' => $this->storeUpdate(
                    $requestData, $actionType, $targetUser
                ),

                'disable' => $this->storeDisable(
                    $request, $actionType, $targetUser
                ),

                default => abort(404),
            };

            return response()->json($application);

        } catch (\LogicException $e) {
            // 「既に処理済み」「ステータスが不整合」などの業務エラー ➔ 422
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Throwable $e) {
            // その他のエラーをLogを保存、messegeとして読み出せるように
            Log::error('申請処理エラー', [
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'システムエラーが発生しました。管理者にお問い合わせください。'
            ], 500);
        }
    }

    // 新規登録
    public function storeCreate(StoreRequest $requestData, string $actionType): JsonResponse
    {
        $validated = $requestData->validated();

        UserChangeApplication::create([
            'action_type' => $actionType,
            'target_user_id' => null,
            'payload' => $validated,
            'status' => UserChangeApplication::STATUS_PENDING,
            'requested_by' => $requestData->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'ユーザー登録の申請を送信しました。',
        ]);
    }

    public function storeUpdate(UpdateRequest $requestData, string $actionType, User $targetUser)
    {
        $validated = $requestData->validated();

        UserChangeApplication::create([
            'action_type' => $actionType,
            'target_user_id' => $targetUser->id,
            'payload' => $validated,
            'status' => UserChangeApplication::STATUS_PENDING,
            'requested_by' => $requestData->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'ユーザー情報更新の申請を送信しました。',
        ]);
    }

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

    // 申請内容の更新
    // public function update(UpdateSubmitRequest $changeRequest): JsonResponse
    public function update(UpdateSubmitRequest $request, UserChangeApplication $changeRequest): JsonResponse
    {
        $validated = $request->validated();

        $changeRequest->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => '申請内容を更新しました。',
        ]);
    }
}
