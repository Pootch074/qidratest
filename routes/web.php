<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;


Route::post('/queue/store', [QueueController::class, 'store'])->name('queue.store');



// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', function () {return view('rmt.assessments.profile');})->name('profile');
    Route::get('/assessments', function () {return view('rmts.assessments.assessments');})->name('assessments');
    Route::get('deadlines', function () {return view('rmt/deadlines');})->name('deadlines');
});

// Rouete Login
Route::get('/', function () {return Auth::check() ? redirect('/dashboard') : redirect(route('login'));});
Route::get('/auth/login', [LoginController::class, 'login'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Routes Preassess
Route::middleware(['auth', CheckUserType::class . ':1,5'])->group(function () {
    Route::get('preassess', [UsersController::class, 'preassess'])->name('preassess');
    Route::get('admin', [UsersController::class, 'admin'])->name('admin');
});
