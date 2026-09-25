<?php
// /var/www/src/app/Http/Controllers/Admin/UserChange/UserChangeApplicationController.php
namespace App\Http\Controllers\Admin\UserChange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserChange\CreateRequest;
use App\Http\Requests\Admin\UserChange\UpdateRequest;
use App\Http\Requests\Admin\UserChange\UpdateSubmitRequest;
use App\Notifications\UserChangeAppricationSubmitted;

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
use Illuminate\Support\Facades\Notification;

use function Laravel\Prompts\alert;

class UserChangeApplicationController extends Controller
{
    // 一覧
    public function index(): Response
    {
        $changeApplications = UserChangeApplication::query()
            ->defaultSort()
            ->paginate(15);  // モデルにカプセル化したScopeを呼び出す

        return response()->view('admin.applications.users.index', compact('changeApplications'));
    }

    // show と editを兼用
    public function edit(Request $request, UserChangeApplication $changeApplication): Response
    {
        Gate::authorize('view', $changeApplication);

        $changeApplication->load(['targetUser', 'requester']);

        if($changeApplication->status === 'rejected') {
            return response()->view('admin.applications.users.reapply', compact('changeApplication'));
        } else {
            return response()->view('admin.applications.users.edit', compact('changeApplication'));
        }
    }

    // 申請作成画面
    /**
     * @param string $actionType [create, update]
     */
    public function create(string $actionType, ?User $targetUser = null): Response|RedirectResponse
    {
        $applicationData = [];

        if ($targetUser !== null) {
            $targetUser->load('roles');
            $applicationData['targetUser'] = $targetUser;
        }

        $applicationData['actionType'] = $actionType;

        return response()->view('admin.applications.users.create', compact('applicationData'));
    }

    // 新規登録post
    public function storeCreate(CreateRequest $applicationData): JsonResponse
    {
        $actionType = 'create';

        try {
            $validated = $applicationData->validated();

            $application = UserChangeApplication::create([
                'action_type' => $actionType,
                'target_user_id' => null,
                'payload' => $validated,
                'status' => UserChangeApplication::STATUS_PENDING,
                'applied_by' => $applicationData->user()->id,
            ]);

            // 成功処理
            // オーナーへ申請を行った通知をする(notification database channels)
            $owners = User::role('owner')->get();
            Notification::send($owners, new UserChangeAppricationSubmitted($application));

            return response()->json([
                'status' => 'success',
                'message' => 'ユーザー登録の申請を送信しました。',
                'application_id' => $application->id,
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
                'user_id' => $applicationData->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'サーバーエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }
    }

    // ユーザー情報の更新
    public function storeUpdate(UpdateRequest $applicationData, User $targetUser)
    {
        $actionType = 'update';
        try {
            $validated = $applicationData->validated();

            $application = UserChangeApplication::create([
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id,
                'payload' => $validated,
                'status' => UserChangeApplication::STATUS_PENDING,
                'applied_by' => $applicationData->user()->id,
            ]);

            //成功処理
            // オーナーへ申請を行った通知をする(notification database channels)
            $owners = User::role('owner')->get();
            Notification::send($owners, new UserChangeAppricationSubmitted($application));

            return response()->json([
                'status' => 'success',
                'message' => 'ユーザー情報更新の申請を送信しました。',
                'application_id' => $application->id,
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
                'user_id' => $applicationData->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'サーバーエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }
    }

    // ユーザー削除の申請
    public function storeDisable(Request $request, User $targetUser)
    {
        $actionType = 'disable';
        try {
            $application = UserChangeApplication::create([
                'action_type' => $actionType,
                'target_user_id' => $targetUser->id,
                'status' => UserChangeApplication::STATUS_PENDING,
                'applied_by' => $request->user()->id,
            ]);

            // 成功処理
            // オーナーへ申請を行った通知をする(notification database channels)
            $owners = User::role('owner')->get();
            Notification::send($owners, new UserChangeAppricationSubmitted($application));

            return response()->json([
                'status' => 'success',
                'message' => 'ユーザー削除の申請を送信しました。',
                'application_id' => $application->id,
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
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'サーバーエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }
    }

    /**
     * 申請内容の更新
     *
     * @param User|null $targetUser FormRequestにてID等の重複のバリデーションを行うためにルートモデルバインディングしている
     */
    public function update(
        UpdateSubmitRequest $request,
        UserChangeApplication $changeApplication,
        ?User $targetUser = null
    ): JsonResponse {

        Gate::authorize('update', $changeApplication);

        try {
            $validated = $request->validated();

            if (!$request->filled('password')) {
                unset($validated['password']);
            }

            $changeApplication->update([
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
    public function destroy(Request $request, UserChangeApplication $changeApplication)
    {
        Gate::authorize('delete', $changeApplication);

        $changeApplication->delete();

        return response()->json([
            'status' => 'success',
            'message' => '申請を削除しました。',
        ]);
    }

    // 却下状態を確認した記録
    public function acknowledge(UserChangeApplication $changeApplication)
    {
        Gate::authorize('history', $changeApplication);

        $changeApplication->update([
            'rejection_acknowledge_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '却下された申請を確認済みに更新しました。',
        ]);
    }

    // 再申請した記録
    public function reapply(UserChangeApplication $parentApplication, UserChangeApplication $childApplication)
    {
        Gate::authorize('history', $parentApplication);
        Gate::authorize('history', $childApplication);

        $parentApplication->update([
            'reapplied_at' => now(),
        ]);

        $childApplication->update([
            'parent_application_id' => $parentApplication->id,
            'rejection_reason' => $parentApplication->rejection_reason,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '再申請の履歴を記録しました。',
        ]);

    }
}
