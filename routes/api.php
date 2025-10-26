<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', [UsersController::class, 'get'])->name('api-users-get');
    Route::post('/users', [UsersController::class, 'post'])->name('api-users-post');
    Route::put('/users/{id}', [UsersController::class, 'put'])->name('api-users-put');
    Route::delete('/users/{id}', [UsersController::class, 'delete'])->name('api-users-delete');
});

Route::get('/register', [UsersController::class, 'register']);
