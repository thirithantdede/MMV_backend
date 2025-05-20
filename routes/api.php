<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Store\StoreCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('api.login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(StoreCategoryController::class)->group(function () {
        Route::get('/store-category', 'index')->name('store-category.list');
    });
});
