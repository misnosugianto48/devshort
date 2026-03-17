<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes (unauthenticated only)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Email verification
    Route::get('verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Dashboard (requires verified email)
    Route::middleware('verified')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        // Links management
        Route::post('links/bulk', [\App\Http\Controllers\LinkController::class, 'bulkAction'])->name('links.bulk');
        Route::resource('links', \App\Http\Controllers\LinkController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::get('links/{link}/export', [\App\Http\Controllers\LinkController::class, 'export'])->name('links.export');
    });
});

// Short Link Redirect Routes
Route::post('/links/{link}/password', [\App\Http\Controllers\LinkPasswordController::class, 'verify'])->name('link.password.verify');

// Link Preview Route
Route::get('/{shortCode}+', [\App\Http\Controllers\PreviewController::class, 'show'])
    ->where('shortCode', '[a-zA-Z0-9\-_]{3,}')
    ->name('link.preview');

// Short Link Redirect Route - MUST BE LAST
// Alphanumeric, hyphens, and underscores allowed, minimum 3 characters
Route::get('/{shortCode}', \App\Http\Controllers\RedirectController::class)
    ->where('shortCode', '[a-zA-Z0-9\-_]{3,}')
    ->name('link.redirect');
