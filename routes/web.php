<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\EncodersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PacdController;



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
    Route::post('admin/users', [UsersController::class, 'store'])->name('admin.store');
    Route::get('pacd', [PacdController::class, 'index'])->name('pacd');
    Route::post('/pacd/generate/{section}', [PacdController::class, 'generateQueue'])->name('pacd.generate');
    Route::post('/admin/users/store', [UsersController::class, 'store'])->name('admin.store');
});
