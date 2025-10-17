<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorController; // ⬅️ tambahin ini

// Redirect ke login saat membuka root
Route::get('/', function () {
    return redirect('/login');
});

// ========== Auth Routes ==========
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost']);

// 💡 RUTE LUPA PASSWORD DITAMBAHKAN DI SINI (TIDAK DILINDUNGI)
Route::get('/profil/index', function () {
    // Anda bisa mengganti ini dengan pemanggilan Controller khusus ForgotPassword
    return "Halaman Lupa Password sedang dikembangkan.";
})->name('password.request'); 
// ========== Protected Routes ==========
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/datasensor', [DashboardController::class, 'dataSensor'])->name('data.sensor');
Route::get('/grafik', [DashboardController::class, 'grafik'])->name('grafik');
Route::get('/datasensorpdf', [DashboardController::class, 'downloadPDF'])->name('data.sensor.pdf');
    // Monitoring
    Route::get('/monitoring', function () {
        return view('monitoring');
    })->name('monitoring');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profil.update');

    // ========== Sensor Routes ==========
    Route::get('/sensors', [SensorController::class, 'index'])->name('sensors.index');
    Route::post('/sensors', [SensorController::class, 'store'])->name('sensors.store');
});



