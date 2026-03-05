<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\TravelOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->prefix('admin') // All URLs now start with /admin/...
    ->name('admin.')  // All route names now start with admin....
    ->group(function () {

        // Final URL: /admin/dashboard | Name: admin.dashboard
        Route::livewire('/dashboard', 'pages::-admin.dashboard')->name('dashboard');

        // Final URL: /admin/help | Name: admin.help
        Route::livewire('/help', 'pages::-admin.help')->name('help');

        // Final URL: /admin/user-profile | Name: admin.user-profile
        Route::livewire('/user-profile', 'pages::-admin.user-profile')->name('user-profile');

        Route::livewire('/travel-orders', 'pages::-admin.travel-orders')->name('travel-orders');

        // --- ADMIN & SUPER ADMIN ONLY ---
        Route::middleware(['can:access-admin-panels'])->group(function () {

            // Final URL: /admin/users | Name: admin.users
            // REMOVED the extra '/admin/users' line to avoid duplication
            Route::livewire('/users', 'pages::-admin.users')->name('users');

            // Final URL: /admin/settings | Name: admin.settings
            Route::livewire('/settings', 'pages::-admin.settings')->name('settings');
        });
    });

// Only guests can access these
Route::middleware('guest')

    ->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
    Route::livewire('/register', 'auth::register')->name('register');
});


// Email verification routes (for authenticated users only)

// 1. The "Verify your email" notice page
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // Create this blade file
})->middleware('auth')->name('verification.notice');

// 2. The logic that handles the actual link click from the email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 3. A route to resend the verification email if they lost it
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


use App\Http\Controllers\TravelOrderPrintController;

Route::get('/travel-order/{order}/print', [TravelOrderPrintController::class, 'print'])
    ->name('travel-order.print')
    ->middleware(['auth']);





















// Route::get('/travel-orders/{id}/print', function ($id) {
//     // 1. Get the order or fail
//     $order = \App\Models\TravelOrder::findOrFail($id);
//     $user = auth()->user();

//     // 2. Security Check
//     $isAuthorized = ($order->user_id === $user->id) ||
//                     str_contains($order->approved_by, $user->fullname) ||
//                     str_contains($order->recommending_approval ?? '', $user->fullname) ||
//                     $user->isSuperAdmin();

//     if (!$isAuthorized) {
//         abort(403, 'You do not have permission to view this Travel Order.');
//     }

//     // 3. Get the Station Officers list from your Component
//     // Replace 'App\Livewire\TravelOrderComponent' with your actual component namespace
//     $officers = ('dar_hr.officers');

//     // 4. Generate the PDF and pass both 'order' and 'officers'
//     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.travel-order', [
//         'order' => $order,
//         'officers' => $officers
//     ])->setPaper('a4', 'portrait');

//     return $pdf->stream("TO-{$order->travel_order_no}.pdf");
// })->middleware(['auth'])->name('travel-order.print');




// Route::middleware(['auth', 'can:access-admin-panel'])->group(function () {
//     Route::livewire('/admin/settings', 'pages::-admin.settings');
// });

// Route::middleware(['auth', 'verified'])
//     ->prefix('admin') // Adds /admin to all URLs
//     ->name('admin.')  // Adds admin. to all route names
//     ->group(function () {
//         // --- SHARED ROUTES (User, Admin, Super Admin) ---
//         Route::livewire('/admin/dashboard', 'pages::-admin.dashboard')->name('dashboard');
//         Route::livewire('/profile', 'pages::-admin.user-profile')->name('user-profile');
//         Route::livewire('/settings', 'pages::-admin.settings')->name('settings');
//         Route::livewire('/help', 'pages::-admin.help')->name('help');

//         // --- ADMIN & SUPER ADMIN ONLY ---
//         // This group restricts standard 'users' out
//         Route::middleware(['can:access-admin-panels'])->group(function () {
//             Route::livewire('/admin/users', 'pages::-admin.users')->name('admin.users');
//             // Add other admin-only tables here
//         });

//         // --- SUPER ADMIN ONLY ---
//         // Highest level of restriction
//         Route::middleware(['can:manage-system'])->group(function () {
//             Route::livewire('/admin/logs', LogsComponent::class)->name('admin.logs');
//             Route::livewire('/admin/advanced-settings', AdvancedSettings::class)->name('admin.advanced');
//         });
// });
