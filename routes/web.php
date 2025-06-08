<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\QuestionnairesController;
use App\Http\Controllers\PeriodsController;
use App\Http\Controllers\ReportsController;

use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect(route('login'));
});

Route::get('/auth/login', [LoginController::class, 'login'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/auth/redirect', [GoogleController::class, 'redirect']);
Route::get('/auth/callback', [GoogleController::class, 'callback']);

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', function () {
        return view('rmt/assessment/profile');
    })->name('profile');

    Route::get('/assessments', function () {
        return view('rmt/assessment/assessments');
    })->name('assessments');

    Route::get('deadlines', function () {
        return view('rmt/deadlines');
    })->name('deadlines');
});

// Routes Admin
Route::middleware(['auth', CheckUserType::class . ':1'])->group(function () {
    Route::get('users', [UsersController::class, 'index'])->name('users');
    Route::get('lgu', [UsersController::class, 'lgu'])->name('lgu');
    Route::get('questionnaires', [QuestionnairesController::class, 'index'])->name('questionnaires');
    Route::get('period-management', [PeriodsController::class, 'index'])->name('period-management');
    Route::get('period-assessments', [PeriodsController::class, 'assessments'])->name('period-assessments');
    Route::get('rmt', [PeriodsController::class, 'assignments'])->name('rmt');

    Route::get('questionnaires/manage/{id}', [QuestionnairesController::class, 'manageQuestionnaires'])->name('manage-questionnaires');
    Route::get('questionnaires/manage/{id}/ref/{id2}', [QuestionnairesController::class, 'getReference'])->name('get-reference');

    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('parameter-report', [ReportsController::class, 'paramReport'])->name('parameter-report');
    Route::get('compliance-monitoring', [ReportsController::class, 'complMonitor'])->name('compliance-monitoring');
});
