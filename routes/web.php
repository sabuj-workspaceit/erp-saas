<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// 6.1 Main domain (marketing + registration + system admin)
Route::domain(config('app-domain.base'))->group(function () {
    // Route::get('/', fn() => view('welcome'));
    Route::get('/', function () {
        return view('welcome');
    });

    // Route::controller(RegisterController::class)->group(function () {
    //     Route::get('/register', 'show')->name('register.show');
    //     Route::post('/register', 'store')->name('register.store');
    // });


    // Route::middleware(['auth'])->group(function () {
    //     Route::get('/admin', fn() => view('admin.index'))
    //         ->middleware('can:system-area'); // gate for system_admin
    // });
});


// 6.2 Tenant area (subdomains)
Route::domain('{tenant}.' . config('app-domain.base'))->group(function () {
    Route::get('/', function () {
        $tenant = app('tenant'); // from TenantMiddleware
        return view('tenant.welcome', compact('tenant'));
    });
    Route::middleware(['auth'])->group(function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
    });
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
