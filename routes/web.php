<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorController; 

// Redirect ke login saat membuka root
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== Auth Routes (Tidak Diproteksi) ==========
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- FITUR RESET PASSWORD SEDERHANA ---
Route::get('/forgot-password', [AuthController::class, 'showSimpleResetForm'])->name('password.request'); 
Route::post('/forgot-password', [AuthController::class, 'simpleResetPassword'])->name('password.update');


// ========== Protected Routes (Hanya untuk User Terotentikasi) ==========
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Data
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/datasensor', [DashboardController::class, 'dataSensor'])->name('data.sensor');
    Route::get('/grafik', [DashboardController::class, 'grafik'])->name('grafik');
    Route::get('/datasensorpdf', [DashboardController::class, 'downloadPDF'])->name('data.sensor.pdf');
    
    // BARU: Route untuk menghapus data sensor berdasarkan bulan (Menggunakan method DELETE)
    Route::delete('/datasensor/delete-by-month', [DashboardController::class, 'deleteByMonth'])->name('delete.sensor.month');
    
    // Monitoring
    Route::get('/monitoring', function () {
        return view('monitoring');
    })->name('monitoring');

    // Profil 
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index'); 
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update'); 

    // ========== Sensor Routes (Web) ==========
    Route::get('/sensors', [SensorController::class, 'index'])->name('sensors.index');
    Route::post('/sensors', [SensorController::class, 'store'])->name('sensors.store');
});