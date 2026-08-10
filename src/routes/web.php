<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/work-logs/index/{log}', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimple');
Route::get('/work-logs/index/', [WorkController::class, 'indexSimple'])->name('work-logs.indexSimpleAll');

Route::get('/work-logs/show/{log}', [WorkController::class, 'show'])->name('work-logs.show');
Route::get('/work-logs/edit/{log}', [WorkController::class, 'edit'])->name('work-logs.edit');
Route::put('/work-logs/edit/{log}', [WorkController::class, 'update'])->name('work-logs.update');

Route::get('/work-logs/create', [WorkController::class, 'create'])->name('create');
Route::post('/work-logs/create', [WorkController::class, 'store'])->name('store');

Route::get('/', [DashboardController::class, 'home'])->name('dashboard');
// Route::get('/', function () {
//     return view('dashboard');
// })->name('dashboard');
// Route::post('/create', function (Request $request) {
//     dd($request);
// })->name('store');
