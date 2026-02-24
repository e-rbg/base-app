<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/admin/dashboard', 'pages::-admin.dashboard')->name('admin.dashboard');
Route::livewire('/admin/settings', 'pages::-admin.settings')->name('admin.settings');
Route::livewire('/admin/users', 'pages::-admin.users')->name('admin.users');
Route::livewire('/admin/help', 'pages::-admin.help')->name('admin.help');
Route::livewire('/admin/user-profile', 'pages::-admin.user-profile')->name('admin.user-profile');