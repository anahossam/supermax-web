<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::view('/', 'dashboard')->name('dashboard');

    // Placeholders for the screens built in later phases.
    Route::view('/production-orders', 'placeholder', ['title' => 'أوامر التشغيل'])->name('production-orders.index');
    Route::view('/maintenance-orders', 'placeholder', ['title' => 'أوامر الصيانة'])->name('maintenance-orders.index');
    Route::view('/customers', 'placeholder', ['title' => 'العملاء'])->name('customers.index');

    Route::middleware('can:admin')->group(function () {
        Route::view('/lists', 'placeholder', ['title' => 'القوائم'])->name('lists.index');
        Route::view('/users', 'placeholder', ['title' => 'المستخدمون'])->name('users.index');
        Route::view('/audit-log', 'placeholder', ['title' => 'سجل التعديلات'])->name('audit.index');
    });
});
