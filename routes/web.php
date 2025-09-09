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
use App\Http\Controllers\IdscanController;
use App\Http\Controllers\SuperAdminController;

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Public Routes
Route::get('/', function () {return Auth::check()? redirect()->intended()
        : redirect(route('login'));
});

// Authentication Routes
Route::get('/auth/login', [LoginController::class, 'login'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Routes for different user types
Route::middleware(['auth', CheckUserType::class . ':0,1,2,3,5,6'])->group(function () {
    Route::get('superadmin', [SuperAdminController::class, 'index'])->name('superadmin');
    Route::get('admin', [UsersController::class, 'admin'])->name('admin');
    Route::get('idscan', [IdscanController::class, 'index'])->name('idscan');
    Route::get('pacd', [PacdController::class, 'index'])->name('pacd');
    Route::get('user', [UsersController::class, 'user'])->name('user');
    Route::get('display', [DisplayController::class, 'index'])->name('display');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/users', [UsersController::class, 'users'])->name('admin.users');
    Route::get('admin/users/json', [UsersController::class, 'usersJson'])->name('admin.users.json');
    Route::post('admin/users/store', [UsersController::class, 'store'])->name('admin.users.store');
    Route::delete('admin/users/{user}', [UsersController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/queues/data', [UsersController::class, 'fetchQueues'])->name('queues.data');
    Route::post('/users/next-regular', [UsersController::class, 'nextRegular'])->name('users.nextRegular');
    Route::post('/users/next-priority', [UsersController::class, 'nextPriority'])->name('users.nextPriority');
    Route::post('/queue/skip', [UsersController::class, 'skipQueue'])->name('users.skipQueue');
    Route::post('/queue/proceed', [UsersController::class, 'proceedQueue'])->name('users.proceedQueue');






    Route::get('admin/steps', [StepsController::class, 'steps'])->name('admin.steps');
    Route::post('admin/steps', [StepsController::class, 'store'])->name('steps.store');
    Route::put('/steps/{id}', [StepsController::class, 'update'])->name('steps.update');
    Route::delete('/steps/{id}', [StepsController::class, 'destroy'])->name('steps.destroy');
    Route::get('/steps/check/{sectionId}/{stepNumber}', [StepsController::class, 'check']);

    Route::get('admin/windows', [WindowsController::class, 'index'])->name('admin.windows');
    Route::post('admin/windows', [WindowsController::class, 'store'])->name('windows.store');
    Route::delete('admin/windows/{id}', [WindowsController::class, 'destroy'])->name('windows.destroy');
    Route::get('/windows/check/{stepId}/{windowNumber}', [WindowsController::class, 'check'])->name('windows.check');

    Route::get('/steps', [DisplayController::class, 'getStepsBySectionId'])->name('steps');
    Route::get('/display/transactions/latest', [DisplayController::class, 'getLatestTransaction']);

    Route::post('/pacd/generate/{section}', [PacdController::class, 'generateQueue'])->name('pacd.generate');
    Route::get('pacd/transactions/table', [PacdController::class, 'transactionsTable'])->name('pacd.transactions.table');
    Route::get('pacd/sections/cards', [PacdController::class, 'sectionsCards'])->name('pacd.sections.cards');
    // Route::get('/pacd/clients/table', [PacdController::class, 'clientsTable'])->name('pacd.clients.table');


    Route::post('/queue/store', [TransactionsController::class, 'store'])->name('queue.store');

    Route::post('superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');

    Route::get('/windows/by-step/{step}', [UsersController::class, 'getWindowsByStep'])
     ->name('windows.byStep');
     

});
