<?php

// use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\UserChange\UserChangeApplicationController;
use App\Http\Controllers\Admin\UserChangeRequestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController as ControllersUserController;
use App\Http\Controllers\WorkController;
use App\Models\Admin\UserChange\UserChangeApplication;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


// ----------------------------------------------------
// ゲスト向けルート
// ----------------------------------------------------
Route::middleware('guest')->group(function (){
    Route::get('/register', [RegisterController::class, 'show'])->name('show.register');
    Route::post('/register', [RegisterController::class, 'create']);

    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::get('/', [LoginController::class, 'showLogin']);
    // login ==> post because of sanctum
    Route::post('/', [LoginController::class, 'authenticate']);
    Route::post('/login', [LoginController::class, 'authenticate']);
});

// ----------------------------------------------------
// メール認証ルート
// ----------------------------------------------------
/*
Route::controller(EmailVerificationController::class)
	->prefix('email')->name('verification.')->group(function () {
});
*/

// 確認メール送信画面を表示する
Route::get('/email/verify', function(Request $request) {
    if ($request->user()->hasVerifiedEmail()) return redirect('/dashboard');
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');

// 確認メールの承認リンクをクリックしてアクセスしたとき
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->intended('/dashboard?verified=1');
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// 確認メールの再送信
//   登録情報に不備がなければ、まず１回自動的に呼び出される
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
        return response()->json([
        'status' => 'verification-link-sent',
        'message' => 'メールを送信しました。確認してください。'
    ]);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');



// ----------------------------------------------------
// 認証済みユーザー向けルート (auth:sanctum ミドルウェアで保護)
// ----------------------------------------------------
Route::middleware(['auth:sanctum'])->group(function () {

    // logout ==> post because of sanctum
    Route::post('/logout', [LoginController::class, 'logout']);

    // Aplinejsから Auth::user()メソッドによるユーザー情報の取得ができないのを補完するため
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'roles' => $user->getRoleNames(), // ['owner'] や ['worker'] などを返す
            'permissions' => $user->getAllPermissions()->pluck('name'), // 付与されている全パーミッション名
        ];
    });

    // -----------------------------
    // ユーザー情報
    //------------------------------
    // 登録・変更申請 管理者専用グループ
    Route::middleware(['role:manager'])
        ->prefix('admin/requests/users')
        ->name('admin.requests.users.')
        ->group(function () {

            Route::get('/', [UserChangeApplicationController::class, 'index'])
            ->name('index');

            Route::get('/{actionType}/{targetUser?}', [UserChangeApplicationController::class, 'create'])
                ->name('create');

            Route::post('/store-create', [UserChangeApplicationController::class, 'storeCreate'])
                ->name('store-create');
            Route::post('/{targetUser}/store-update', [UserChangeApplicationController::class, 'storeUpdate'])
                ->name('store-update');

            // 申請内容の更新
            Route::get('/record/edit/{changeRequest}', [UserChangeApplicationController::class, 'edit'])
                ->name('edit');
            Route::patch('/record/{changeRequest}/update/{targetUser?}', [UserChangeApplicationController::class, 'update'])
                ->name('update');
    });
    // 承認 オーナー専用グループ
    Route::middleware(['role:owner'])
        ->prefix('admin/approvals')
        ->name('admin.approvals.')
        ->group(function () {
            Route::get('/users', [UserApprovalController::class, 'index'])
                ->name('users.index');
            Route::get('/users/{changeRequest}', [UserApprovalController::class, 'show'])
                ->name('users.show');
            Route::patch('/users/{changeRequest}/approve', [UserApprovalController::class, 'approve'])
                ->name('users.approve');
            Route::patch('/users/{changeRequest}/reject', [UserApprovalController::class, 'reject'])
                ->name('users.reject');
        });
    // ユーザー情報閲覧
    Route::prefix('users')
        ->name('users.')
        ->group(function () {
        Route::get('/index', [ControllersUserController::class, 'index'])
            ->name('index');
        Route::get('/', [ControllersUserController::class, 'show'])
            ->name('show');
    });

    // Route::get('/work-logs/index', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimpleAll');
    Route::get('/work-logs/index/{cropSeason?}', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimple');

    Route::get('/work-logs/show/{workLog}', [WorkController::class, 'show'])->name('work-logs.show');
    Route::get('/work-logs/edit/{workLog}', [WorkController::class, 'edit'])->name('work-logs.edit');
    Route::put('/work-logs/edit/{workLog}', [WorkController::class, 'update'])->name('work-logs.update');

    Route::get('/work-logs/create', [WorkController::class, 'create'])->name('create');
    Route::post('/work-logs/create', [WorkController::class, 'store'])->name('store');

    Route::delete('/work-logs/delete/{workLog}', [WorkController::class, 'destroy'])->name('work-logs.delete');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::get('/test', function () {
    return view('dashboard');
});


