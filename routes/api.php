<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/register', [\App\Http\Controllers\Api\UsersController::class, 'register']);

Route::get('/users', [\App\Http\Controllers\Api\UsersController::class, 'get']);
Route::post('/users', [\App\Http\Controllers\Api\UsersController::class, 'post']);
Route::put('/users/{id}', [\App\Http\Controllers\Api\UsersController::class, 'put']);
Route::delete('/users/{id}', [\App\Http\Controllers\Api\UsersController::class, 'delete']);

Route::get('/rmt', [\App\Http\Controllers\Api\RmtController::class, 'get']);
Route::post('/rmt', [\App\Http\Controllers\Api\RmtController::class, 'post']);
Route::put('/rmt/{id}', [\App\Http\Controllers\Api\RmtController::class, 'put']);
Route::delete('/rmt/{id}', [\App\Http\Controllers\Api\RmtController::class, 'delete']);

Route::get('/lgu', [\App\Http\Controllers\Api\LguController::class, 'get']);
Route::post('/lgu', [\App\Http\Controllers\Api\LguController::class, 'post']);
Route::put('/lgu/{id}', [\App\Http\Controllers\Api\LguController::class, 'put']);
Route::delete('/lgu/{id}', [\App\Http\Controllers\Api\LguController::class, 'delete']);
