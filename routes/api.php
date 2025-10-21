<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/register', [\App\Http\Controllers\Api\UsersController::class, 'register']);

Route::get('/users', [\App\Http\Controllers\Api\UsersController::class, 'get'])->name('api-users-get');
Route::post('/users', [\App\Http\Controllers\Api\UsersController::class, 'post'])->name('api-users-post');
Route::put('/users/{id}', [\App\Http\Controllers\Api\UsersController::class, 'put'])->name('api-users-put');
Route::delete('/users/{id}', [\App\Http\Controllers\Api\UsersController::class, 'delete'])->name('api-users-delete');
