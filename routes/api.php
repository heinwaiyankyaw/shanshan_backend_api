<?php

use App\Http\Controllers\API\AhtoneLevelController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\PaymentTypeController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RemarkController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SpicyLevelController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'lists']);
        Route::post('store', [CategoryController::class, 'store']);
        Route::get('{id}', [CategoryController::class, 'view']);
        Route::post('edit/{id}', [CategoryController::class, 'edit']);
        Route::delete('delete/{id}', [CategoryController::class, 'delete']);
        Route::post('by-product/{id}', [CategoryController::class, 'categoryByProduct']);
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

    Route::prefix('remark')->group(function () {
        Route::get('/', [RemarkController::class, 'lists']);
        Route::post('store', [RemarkController::class, 'store']);
        Route::get('{id}', [RemarkController::class, 'view']);
        Route::post('edit/{id}', [RemarkController::class, 'edit']);
        Route::delete('delete/{id}', [RemarkController::class, 'delete']);
    });

    Route::prefix('payment-type')->group(function () {
        Route::get('/', [PaymentTypeController::class, 'lists']);
        Route::post('store', [PaymentTypeController::class, 'store']);
        Route::get('{id}', [PaymentTypeController::class, 'view']);
        Route::post('edit/{id}', [PaymentTypeController::class, 'edit']);
        Route::delete('delete/{id}', [PaymentTypeController::class, 'delete']);
    });

    Route::prefix('sale')->group(function () {
        Route::post('/', [SaleController::class, 'store']);
        Route::get('lists', [SaleController::class, 'lists']);

        Route::get('daily', [SaleController::class, 'daily']);
        Route::get('weekly', [SaleController::class, 'weekly']);
        Route::get('pastMonth', [SaleController::class, 'pastMonth']);
        Route::get('currentMonth', [SaleController::class, 'currentMonth']);

    });

    Route::post('logout', [AuthController::class, 'logout']);
});
