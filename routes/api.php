<?php

use App\Http\Controllers\API\AhtoneLevelController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SpicyLevelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'lists']);
        Route::post('store', [CategoryController::class, 'store']);
        Route::get('{id}', [CategoryController::class, 'view']);
        Route::post('edit/{id}', [CategoryController::class, 'edit']);
        Route::delete('delete/{id}', [CategoryController::class, 'delete']);
    });

    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'lists']);
        Route::post('store', [ProductController::class, 'store']);
        Route::get('{id}', [ProductController::class, 'view']);
        Route::post('edit/{id}', [ProductController::class, 'edit']);
        Route::delete('delete/{id}', [ProductController::class, 'delete']);
    });

    Route::prefix('menu')->group(function () {
        Route::get('/', [MenuController::class, 'lists']);
        Route::post('store', [MenuController::class, 'store']);
        Route::get('{id}', [MenuController::class, 'view']);
        Route::post('edit/{id}', [MenuController::class, 'edit']);
        Route::delete('delete/{id}', [MenuController::class, 'delete']);
    });

    Route::prefix('spicy-level')->group(function () {
        Route::get('/', [SpicyLevelController::class, 'lists']);
        Route::post('store', [SpicyLevelController::class, 'store']);
        Route::get('{id}', [SpicyLevelController::class, 'view']);
        Route::post('edit/{id}', [SpicyLevelController::class, 'edit']);
        Route::delete('delete/{id}', [SpicyLevelController::class, 'delete']);
    });

    Route::prefix('ahtone-level')->group(function () {
        Route::get('/', [AhtoneLevelController::class, 'lists']);
        Route::post('store', [AhtoneLevelController::class, 'store']);
        Route::get('{id}', [AhtoneLevelController::class, 'view']);
        Route::post('edit/{id}', [AhtoneLevelController::class, 'edit']);
        Route::delete('delete/{id}', [AhtoneLevelController::class, 'delete']);
    });

    Route::post('logout', [AuthController::class, 'logout']);
});
