<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\BlockedCountryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'check_user_active'])->name('dashboard');

Route::middleware(['auth', 'check_user_active'])->group(function () {
    Route::resource('users', UserController::class);

    Route::get('/update', [UpdateController::class, 'show'])->name('update.show');
    Route::post('/update', [UpdateController::class, 'upload'])->name('update.upload');
    Route::get('/latest-update', [UpdateController::class, 'getLatestUpdate'])->name('update.getLatestUpdate');
    Route::post('/update/edit-version', [UpdateController::class, 'editVersion'])->name('update.editVersion');
});

Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
});

Route::get('logs/delete-all', [LogController::class, 'deleteAllLogs'])->name('logs.deleteAll');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clearCache');

    // Blocked Countries Management
    Route::resource('blocked-countries', BlockedCountryController::class);
});

// Country Blocked Page - accessible without authentication
Route::get('/country-blocked', function () {
    return view('blocked-country');
})->name('country.blocked');

require __DIR__.'/auth.php';
