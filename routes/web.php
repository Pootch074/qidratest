<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PacdController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\WindowsController;
use App\Http\Controllers\DisplayController;



Route::post('/queue/store', [TransactionsController::class, 'store'])->name('queue.store');



// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Rouete Login
Route::get('/', function () {
    return Auth::check()
        ? redirect()->intended() // redirect to the place authenticated() sends them
        : redirect(route('login'));
});

Route::get('/auth/login', [LoginController::class, 'login'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Routes Preassess
Route::middleware(['auth', CheckUserType::class . ':1,2,3,4,5,6,7'])->group(function () {
    Route::get('admin', [UsersController::class, 'admin'])->name('admin');
    Route::get('preassess', [UsersController::class, 'preassess'])->name('preassess');
    Route::get('encode', [UsersController::class, 'encode'])->name('encode');
    Route::get('assessment', [UsersController::class, 'assessment'])->name('assessment');
    Route::get('release', [UsersController::class, 'release'])->name('release');
    Route::get('user', [UsersController::class, 'user'])->name('user');
});


Route::middleware(['auth'])->group(function () {
    Route::get('admin/users', [UsersController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/json', [UsersController::class, 'usersJson'])->name('admin.users.json');

    Route::get('admin/steps', [StepsController::class, 'steps'])->name('admin.steps');
    Route::get('admin/windows', [WindowsController::class, 'index'])->name('admin.windows');
    Route::post('admin/windows', [WindowsController::class, 'store'])->name('windows.store');
    Route::delete('admin/windows/{id}', [WindowsController::class, 'destroy'])->name('windows.destroy');



    Route::post('admin/steps', [StepsController::class, 'store'])->name('steps.store');
    Route::put('/steps/{id}', [StepsController::class, 'update'])->name('steps.update');
    // routes/web.php
    Route::delete('/steps/{id}', [StepsController::class, 'destroy'])->name('steps.destroy');
    Route::get('/steps/check/{sectionId}/{stepNumber}', [StepsController::class, 'check']);





    Route::post('admin/users', [UsersController::class, 'store'])->name('admin.store');
    Route::get('pacd', [PacdController::class, 'index'])->name('pacd');
    Route::post('/pacd/generate/{section}', [PacdController::class, 'generateQueue'])->name('pacd.generate');
    Route::get('pacd/transactions/table', [PacdController::class, 'transactionsTable'])
        ->name('pacd.transactions.table');

    Route::post('/admin/users/store', [UsersController::class, 'store'])->name('admin.store');
    Route::delete('/admin/users/{user}', [UsersController::class, 'destroy'])->name('admin.users.destroy');


    Route::get('/queues/data', [UsersController::class, 'fetchQueues'])->name('queues.data');

    Route::get('/admin/display', [DisplayController::class, 'index'])->name('admin.display.index');
});
