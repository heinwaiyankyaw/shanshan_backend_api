<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
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

    Route::post('logout', [AuthController::class, 'logout']);
});