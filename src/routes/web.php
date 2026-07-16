<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/create', [WorkController::class, 'create'])->name('create');
Route::post('/create', [WorkController::class, 'store'])->name('store');
// Route::post('/create', function (Request $request) {
//     dd($request);
// })->name('store');
