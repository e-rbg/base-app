<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\TravelOrderPrintController;
use App\Http\Controllers\TravelOrderController;

Route::get('/', function () {
    return view('welcome');
});

// --- AUTHENTICATED ADMIN AREA ---
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::livewire('/dashboard', 'pages::-admin.dashboard')->name('dashboard');
        Route::livewire('/help', 'pages::-admin.help')->name('help');
        Route::livewire('/user-profile', 'pages::-admin.user-profile')->name('user-profile');
        // routes/web.php
        Route::livewire('/print-travel-orders/{id}/print', 'pages::-admin.print-travel-order')
            ->name('print-travel-order');
        
        // This is your main Livewire List
        Route::livewire('/travel-orders', 'pages::-admin.travel-orders')->name('travel-orders');

        // --- ADMIN & SUPER ADMIN ONLY ---
        Route::middleware(['can:access-admin-panels'])->group(function () {
            Route::livewire('/users', 'pages::-admin.users')->name('users');
            Route::livewire('/settings', 'pages::-admin.settings')->name('settings');
        });
});

// --- GUEST ROUTES ---
Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
    Route::livewire('/register', 'auth::register')->name('register');
});

// --- TRAVEL ORDER DOCUMENT ROUTES (PRINT/PDF) ---
// I grouped these outside the 'admin.' name prefix to keep names simple
Route::middleware(['auth'])->prefix('documents')->group(function () {
    
    // Web Print View (The one we just built)
    Route::get('/travel-order/{travelOrder}/print', [TravelOrderPrintController::class, 'print'])
        ->name('travel-order.print');

    // Actual PDF Download
    Route::get('/travel-order/{id}/pdf', [TravelOrderPrintController::class, 'downloadPdf'])
        ->name('travel-order.pdf');
});

// --- EMAIL VERIFICATION ---
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');