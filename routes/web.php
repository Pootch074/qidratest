<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\IdscanController;
use App\Http\Controllers\PacdController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WindowsController;
use App\Http\Controllers\OtpController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Session Checking Route
|--------------------------------------------------------------------------
*/

Route::get('/session/check', function () {
    $user = Auth::user();

    if (! $user) {
        return response()->json(['active' => false]);
    }

    if ($user->session_id !== session()->getId()) {
        Auth::logout();
        session()->invalidate();

        return response()->json(['active' => false]);
    }

    return response()->json(['active' => true]);
})->name('session.check');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/otp-verify', [OtpController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpController::class, 'verify'])->name('otp.verify.submit');


Route::get('/', fn () => Auth::check() ? redirect()->intended() : redirect(route('login')));

Route::prefix('auth')->group(function () {
    // Show registration form
    Route::get('/register', [RegisterController::class, 'index'])->name('register');

    // Handle registration form submission
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

    // Login route 
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Generic)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/auto-logout', [LoginController::class, 'logout'])->name('auto.logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes by User Type
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', CheckUserType::class.':0,1,2,3,5,6'])->group(function () {
    Route::get('superadmin', [SuperAdminController::class, 'index'])->name('superadmin');
    Route::get('admin', [UsersController::class, 'admin'])->name('admin');
    Route::get('idscan', [IdscanController::class, 'index'])->name('idscan');
    Route::get('pacd', [PacdController::class, 'index'])->name('pacd');
    Route::get('user', [UsersController::class, 'user'])->name('user');
    Route::get('display', [DisplayController::class, 'index'])->name('display');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // === Users Management ===
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [UsersController::class, 'users'])->name('admin.users');
        Route::get('/json', [UsersController::class, 'usersJson'])->name('admin.users.json');
        Route::post('/store', [UsersController::class, 'store'])->name('admin.users.store');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('admin.users.destroy');
    });

    // === Steps Management ===
    Route::prefix('admin/steps')->group(function () {
        Route::get('/', [StepsController::class, 'steps'])->name('admin.steps');
        Route::post('/', [StepsController::class, 'store'])->name('steps.store');
    });
    Route::put('/steps/{id}', [StepsController::class, 'update'])->name('steps.update');
    Route::delete('/steps/{id}', [StepsController::class, 'destroy'])->name('steps.destroy');
    Route::get('/steps/check/{sectionId}/{stepNumber}', [StepsController::class, 'check']);
    Route::get('/steps/check-name/{sectionId}/{stepName}', [StepsController::class, 'checkName']);

    // === Windows Management ===
    Route::prefix('admin/windows')->group(function () {
        Route::get('/', [WindowsController::class, 'index'])->name('admin.windows');
        Route::post('/', [WindowsController::class, 'store'])->name('windows.store');
        Route::delete('/{id}', [WindowsController::class, 'destroy'])->name('windows.destroy');
    });
    Route::get('/windows/check/{stepId}/{windowNumber}', [WindowsController::class, 'check'])->name('windows.check');

    // === Display Routes ===
    Route::get('/steps', [DisplayController::class, 'getStepsBySectionId'])->name('steps');
    Route::get('/display/transactions/latest', [DisplayController::class, 'getLatestTransaction'])->name('display.latest-transaction');

    // === PACD Routes ===
    Route::prefix('pacd')->group(function () {
        Route::post('/generate/{section}', [PacdController::class, 'generateQueue'])->name('pacd.generate');
        Route::get('/transactions/table', [PacdController::class, 'transactionsTable'])->name('pacd.transactions.table');
        Route::get('/sections/cards', [PacdController::class, 'sectionsCards'])->name('pacd.sections.cards');
        Route::get('/pending/table', [PacdController::class, 'pendingQueues'])->name('pacd.pending.table');
        Route::get('/scanned_id/table', [PacdController::class, 'clientsTable'])->name('pacd.scanned_id.table');
    });
    Route::post('/transactions/{id}/resume', [PacdController::class, 'resumeTransaction'])->name('transactions.resume');

    // === Queue Operations ===
    Route::post('/queue/store', [TransactionsController::class, 'store'])->name('queue.store');
    Route::post('/queue/skip', [UsersController::class, 'skipQueue'])->name('users.skipQueue');
    Route::post('/queue/recall', [UsersController::class, 'recallQueue'])->name('users.recallQueue');
    Route::post('/queue/proceed', [UsersController::class, 'proceedQueue'])->name('users.proceedQueue');

    // === User Actions ===
    Route::post('/users/next-regular', [UsersController::class, 'nextRegular'])->name('users.nextRegular');
    Route::post('/users/next-priority', [UsersController::class, 'nextPriority'])->name('users.nextPriority');
    Route::post('/users/next-returnee', [UsersController::class, 'nextReturnee'])->name('users.nextReturnee');
    Route::post('/users/returnQueue', [UsersController::class, 'returnQueue'])->name('users.returnQueue');

    // === Queues Data ===
    Route::get('/queues/data', [UsersController::class, 'getQueues'])->name('queues.data');
    Route::get('/windows/by-step/{step}', [UsersController::class, 'getWindowsByStep'])->name('windows.byStep');
    Route::post('/queues/serve-again', [UsersController::class, 'serveAgain'])->name('queues.serveAgain');

    // === Upcoming Queues ===
    Route::prefix('queues/upcoming')->group(function () {
        Route::post('/preassess/regu/update', [UsersController::class, 'updateUpcomingPreassessRegu'])->name('queues.updateUpcomingPreassessRegu');
        Route::post('/prio/update', [UsersController::class, 'updateUpcomingPrio'])->name('queues.updateUpcomingPrio');
        Route::post('/returnee/update', [UsersController::class, 'updateUpcomingReturnee'])->name('queues.updateUpcomingReturnee');
        Route::post('/updateUpcoming', [UsersController::class, 'updateUpcoming'])->name('queues.updateUpcoming');
    });

    // === Pending Queues ===
    Route::prefix('queues/pending')->group(function () {
        Route::post('/regu/update', [UsersController::class, 'updatePendingRegu'])->name('queues.updatePendingRegu');
        Route::post('/prio/update', [UsersController::class, 'updatePendingPrio'])->name('queues.updatePendingPrio');
        Route::post('/returnee/update', [UsersController::class, 'updatePendingReturnee'])->name('queues.updatePendingReturnee');
    });

    // === SuperAdmin Actions ===
    Route::post('superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
});
