<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Auth;



Route::post('/queue/store', [TransactionsController::class, 'store'])->name('queue.store');



// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', function () {
        return view('rmt.assessments.profile');
    })->name('profile');
    Route::get('/assessments', function () {
        return view('rmts.assessments.assessments');
    })->name('assessments');
    Route::get('deadlines', function () {
        return view('rmt/deadlines');
    })->name('deadlines');
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
Route::middleware(['auth', CheckUserType::class . ':1,2,3,4,5'])->group(function () {
    Route::get('admin', [UsersController::class, 'admin'])->name('admin');
    Route::get('preassess', [UsersController::class, 'preassess'])->name('preassess');
    Route::get('encode', [UsersController::class, 'encode'])->name('encode');
    Route::get('assessment', [UsersController::class, 'assessment'])->name('assessment');
    Route::get('release', [UsersController::class, 'release'])->name('release');
});


Route::middleware(['auth'])->group(function () {
    Route::get('admin/users', [UsersController::class, 'users'])->name('admin.users');
    Route::post('admin/users', [UsersController::class, 'store'])->name('admin.store');
});
