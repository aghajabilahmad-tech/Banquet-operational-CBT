<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Banquet Operational System (BOS)
|--------------------------------------------------------------------------
*/

// Halaman Utama / Beranda
Route::get('/', function () {
    return view('home');
})->name('home');

// Modul Event (Admin Only: Form Tambah, Simpan, & Batalkan Event BEO)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
});

// Modul Event (Daftar Event & Detail BEO)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Guest Routes (Hanya dapat diakses jika pengguna belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes (Hanya dapat diakses jika pengguna telah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pengalihan jika ada request lama ke dashboard diarahkan langsung ke Beranda
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
});
