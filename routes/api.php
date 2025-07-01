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

Route::get('/rmt', [\App\Http\Controllers\Api\RmtController::class, 'get']);
Route::post('/rmt', [\App\Http\Controllers\Api\RmtController::class, 'post']);
Route::put('/rmt/{id}', [\App\Http\Controllers\Api\RmtController::class, 'put']);
Route::delete('/rmt/{id}', [\App\Http\Controllers\Api\RmtController::class, 'delete']);

Route::get('/lgu', [\App\Http\Controllers\Api\LguController::class, 'get'])->name('api-lgu-get');
Route::post('/lgu', [\App\Http\Controllers\Api\LguController::class, 'post'])->name('api-lgu-post');
Route::put('/lgu/{id}', [\App\Http\Controllers\Api\LguController::class, 'put'])->name('api-lgu-put');
Route::delete('/lgu/{id}', [\App\Http\Controllers\Api\LguController::class, 'delete'])->name('api-lgu-delete');

Route::get('/questionnaires', [\App\Http\Controllers\Api\QuestionnairesController::class, 'get'])->name('api-questionnaires-get');
Route::post('/questionnaires', [\App\Http\Controllers\Api\QuestionnairesController::class, 'post'])->name('api-questionnaires-post');
Route::put('/questionnaires/{id}', [\App\Http\Controllers\Api\QuestionnairesController::class, 'put'])->name('api-questionnaires-put');
Route::delete('/questionnaires/{id}', [\App\Http\Controllers\Api\QuestionnairesController::class, 'delete'])->name('api-questionnaires-delete');
Route::get('/questionnaires/{id}/toggle', [\App\Http\Controllers\Api\QuestionnairesController::class, 'toggleStatus'])->name('toggle-questionnaire-status');

Route::get('/periods', [\App\Http\Controllers\Api\PeriodsController::class, 'get'])->name('api-periods-get');
Route::post('/periods', [\App\Http\Controllers\Api\PeriodsController::class, 'post'])->name('api-periods-post');
Route::put('/periods/{id}', [\App\Http\Controllers\Api\PeriodsController::class, 'put'])->name('api-periods-put');
Route::delete('/periods/{id}', [\App\Http\Controllers\Api\PeriodsController::class, 'delete'])->name('api-periods-delete');

Route::post('/period/assign', [\App\Http\Controllers\Api\PeriodsController::class, 'assign'])->name('api-periods-assign');

Route::get('/deadlines/{userId}', [\App\Http\Controllers\Api\DeadlinesController::class, 'get'])->name('api-deadlines-get');

Route::post('/assessment/mov', [\App\Http\Controllers\Api\AssessmentsController::class, 'mov'])->name('api-assessment-mov');
Route::post('/assessment/level', [\App\Http\Controllers\Api\AssessmentsController::class, 'level'])->name('api-assessment-level');
Route::post('/assessment/remarks', [\App\Http\Controllers\Api\AssessmentsController::class, 'remarks'])->name('api-assessment-remarks');
Route::post('/assessment/recommendation', [\App\Http\Controllers\Api\AssessmentsController::class, 'recommendation'])->name('api-assessment-recommendation');