<?php

// use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


// ----------------------------------------------------
// ゲスト向けルート
// ----------------------------------------------------
Route::middleware('guest')->group(function (){
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::get('/', [LoginController::class, 'showLogin']);
    // login ==> post because of sanctum
    Route::post('/', [LoginController::class, 'authenticate']);
    Route::post('/login', [LoginController::class, 'authenticate']);
});


// ----------------------------------------------------
// 認証済みユーザー向けルート (auth:sanctum ミドルウェアで保護)
// ----------------------------------------------------
Route::middleware(['auth:sanctum'])->group(function () {

    // logout ==> post because of sanctum
    Route::post('/logout', [LoginController::class, 'logout']);

    // Aplinejsから Auth::user()メソッドによるユーザー情報の取得ができないのを補完するため
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/work-logs/index/{log}', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimple');
    Route::get('/work-logs/index', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimpleAll');

    Route::get('/work-logs/show/{log}', [WorkController::class, 'show'])->name('work-logs.show');
    Route::get('/work-logs/edit/{log}', [WorkController::class, 'edit'])->name('work-logs.edit');
    Route::put('/work-logs/edit/{log}', [WorkController::class, 'update'])->name('work-logs.update');

    Route::get('/work-logs/create', [WorkController::class, 'create'])->name('create');
    Route::post('/work-logs/create', [WorkController::class, 'store'])->name('store');

    Route::delete('/work-logs/delete/{ids}', [WorkController::class, 'destroy'])->name('work-logs.delete');

    Route::get('/home', [DashboardController::class, 'home'])->name('dashboard');
});

// Route::get('/', function () {
//     return view('dashboard');
// })->name('dashboard');
// Route::post('/create', function (Request $request) {
//     dd($request);
// })->name('store');
