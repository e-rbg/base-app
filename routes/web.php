<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])
    ->prefix('admin') // Adds /admin to all URLs
    ->name('admin.')  // Adds admin. to all route names
    ->group(function () {
        
        Route::livewire('/dashboard', 'pages::-admin.dashboard')->name('dashboard');
        Route::livewire('/settings', 'pages::-admin.settings')->name('settings');
        Route::livewire('/users', 'pages::-admin.users')->name('users');
        Route::livewire('/help', 'pages::-admin.help')->name('help');
        Route::livewire('/user-profile', 'pages::-admin.user-profile')->name('user-profile');

    });

// Only guests can access these
Route::middleware('guest')
    
    ->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
    Route::livewire('/register', 'auth::register')->name('register');
});