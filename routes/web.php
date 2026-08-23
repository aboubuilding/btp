<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SoustraitantController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/check-session', [LoginController::class, 'checkSession'])->name('check.session');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Les users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::post('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
        Route::get('/search', [UserController::class, 'search'])->name('search');
    });

// Sous traitants
    Route::prefix('soustraitants')->name('soustraitants.')->group(function () {
        Route::get('/', [SoustraitantController::class, 'index'])->name('index');
        Route::post('/', [SoustraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [SoustraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [SoustraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [SoustraitantController::class, 'restore'])->name('restore');
        Route::post('/{id}/update-status', [SoustraitantController::class, 'updateStatus'])->name('update-status');
        Route::get('/search', [SoustraitantController::class, 'search'])->name('search');
        Route::get('/stats', [SoustraitantController::class, 'getStats'])->name('stats');
    });


});

// Route par défaut pour le tableau de bord
Route::get('/', function () {
    return redirect()->route('dashboard');
});
