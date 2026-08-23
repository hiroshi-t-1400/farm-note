<?php

// use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\UserChangeRequestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WorkController;
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
    // return response()->view('/dashboard');
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

    Route::middleware(['role:manager'])->group(function () {
        Route::get('/admin/users/create', [UserChangeRequestController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/create', [UserChangeRequestController::class, 'store'])->name('admin.users.store');
    });

// オーナー専用グループ
Route::middleware(['auth:sanctum', 'role:owner'])
    ->prefix('admin/approvals')
    ->name('admin.approvals.')
    ->group(function () {

        // 1. 承認待ち一覧表示画面
        Route::get('/users', [UserApprovalController::class, 'index'])
            ->name('users.index');

        // 2. 申請内容の詳細確認画面
        Route::get('/users/{changeRequest}', [UserApprovalController::class, 'show'])
            ->name('users.show');

        // 3. 承認実行（usersテーブルへ反映）
        Route::patch('/users/{changeRequest}/approve', [UserApprovalController::class, 'approve'])
            ->name('users.approve');

        // 4. 却下実行
        Route::patch('/users/{changeRequest}/reject', [UserApprovalController::class, 'reject'])
            ->name('users.reject');
    });



    Route::get('/work-logs/index/{log}', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimple');
    Route::get('/work-logs/index', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimpleAll');

    Route::get('/work-logs/show/{log}', [WorkController::class, 'show'])->name('work-logs.show');
    Route::get('/work-logs/edit/{log}', [WorkController::class, 'edit'])->name('work-logs.edit');
    Route::put('/work-logs/edit/{log}', [WorkController::class, 'update'])->name('work-logs.update');

    Route::get('/work-logs/create', [WorkController::class, 'create'])->name('create');
    Route::post('/work-logs/create', [WorkController::class, 'store'])->name('store');

    Route::delete('/work-logs/delete/{ids}', [WorkController::class, 'destroy'])->name('work-logs.delete');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::get('/test', function () {
    return view('/dashboard');
});


