<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Loading\LoadingController;
use App\Http\Controllers\Api\Publish\PublishController;
use App\Http\Controllers\Api\Store\StoreCategoryController;
use App\Http\Controllers\Api\Sync\SyncElmentController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
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

    Route::controller(LoadingController::class)->group(function () {
        Route::get('/project-data', 'index')->name('loading.list');
        Route::get('/project-elements', 'projectElements')->name('loading.list');
    });

    Route::controller(SyncElmentController::class)->group(function () {
        Route::post('/sync-elements', 'syncElements')->name('sync-elements');
        Route::post('/sync-map', 'syncMap')->name('sync-elements');
    });

    Route::get('/auth-check', function () {
        sleep(1);

        return response()->json([
            'status' => 'success',
            'message' => 'User is authenticated',
        ]);
    })->name('auth-check');
});

Route::controller(PublishController::class)->group(function () {
    Route::get("/projects/{uri}", "viewProject")->name("publish.viewProject");
});