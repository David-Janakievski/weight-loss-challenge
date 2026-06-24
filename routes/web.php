<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Guest auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Onboarding (auth required, but NOT gated by the onboarding middleware itself)
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/password', [OnboardingController::class, 'showPasswordForm'])->name('onboarding.password');
    Route::post('/onboarding/password', [OnboardingController::class, 'changePassword'])->name('onboarding.password.submit');

    Route::get('/onboarding/start', [OnboardingController::class, 'showStartForm'])->name('onboarding.start');
    Route::post('/onboarding/start', [OnboardingController::class, 'submitStart'])->name('onboarding.start.submit');
});

// Main app (auth + onboarding complete)
Route::middleware(['auth', 'onboarding.complete'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/checkin', [CheckinController::class, 'create'])->name('checkin.create');
    Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::get('/checkins/{checkin}/edit', [AdminController::class, 'editCheckin'])->name('admin.checkins.edit');
    Route::post('/checkins/{checkin}', [AdminController::class, 'updateCheckin'])->name('admin.checkins.update');
    Route::delete('/checkins/{checkin}', [AdminController::class, 'deleteCheckin'])->name('admin.checkins.delete');

    Route::get('/photos', [AdminController::class, 'photos'])->name('admin.photos');
});
