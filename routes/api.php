<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\GuestUserAuthController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\api\FloorController;
use App\Http\Controllers\Api\Loading\LoadingController;
use App\Http\Controllers\Api\Publish\PublishController;
use App\Http\Controllers\Api\Store\StoreCategoryController;
use App\Http\Controllers\Api\Sync\SyncElmentController;
use App\Http\Controllers\Api\UpdateShopInfoController;
use App\Http\Controllers\Api\UserData\UserDataProviderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('api.login');
    Route::post('/register', [AuthController::class, 'handleRegister'])->name('api.register');
});

Route::middleware('guest')->prefix('shop-user')->group(function () {
    Route::post('/login', [GuestUserAuthController::class, 'handleLogin'])->name('api.guest.login');
    Route::post('/register', [GuestUserAuthController::class, 'handleRegister'])->name('api.guest.register');
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
        Route::post('/clean-elements', 'cleanElements')->name('clean-elements');
        Route::post('/sync-map', 'syncMap')->name('sync-map');
        Route::post('/delete-element', 'deleteElement')->name('delete-element');
    });

    Route::controller(PublishController::class)->group(function () {
        Route::post("/publish-project", "publishProject")->name("publish.publishProject");
    });

    Route::controller(FloorController::class)->group(function () {
        Route::post("/create-new-floor", "createNewFloor")->name("create-new-floor");
    });

    Route::controller(UpdateShopInfoController::class)->group(function () {
        Route::get("/shop-information/{id}", "getShop")->name("get-shop-info");
        Route::post("/get-shop-info", "getShopInfo")->name("shop-info");
        Route::post("/update-shop-info", "updateShopInfo")->name("shop-info");
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get("/dashboard/statistics", "statistics")->name("dashboard.statistics");
        Route::get("/dashboard/popular-stores", "popularStores")->name("dashboard.popular-stores");
        Route::get("/dashboard/visitor-tracks", "visitorTracks")->name("dashboard.visitor-tracks");

        Route::get("/dashboard/get-table-analytic", "getTableAnaylytic")->name("dashboard.get-table-anaylytic");
        Route::get("/dashboard/get-assets-data", "getAssetsData")->name("dashboard.get-assets-data");
    });

    Route::get('/auth-check', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'User is authenticated',
        ]);
    })->name('auth-check');
});

Route::controller(PublishController::class)->group(function () {
    Route::get("/projects", "projectLists")->name("publish.projectLists");
    Route::get("/projects/{uri}", "viewProject")->name("publish.viewProject");
});

Route::controller(UserDataProviderController::class)->group(function () {
    Route::get("/get-promotions/{project_id}", "getPromotionElements")->name("user-data");
    Route::get("/get-events/{project_id}", "getEventElements")->name("user-data");
    Route::get("/search-elements/{project_id}", "searchElements")->name("search-elements");

    Route::post("/sync-routes", "syncRoutes")->name("sync-routes");

    Route::get("/explore-data","exploreData")->name("explore-data");
});